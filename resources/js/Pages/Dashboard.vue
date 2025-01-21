<script setup>

    import { computed, ref } from 'vue'
    import { Head, useForm } from '@inertiajs/vue3'
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
    import List from '@/Components/Shopping/List.vue'
    import PrimaryButton from '@/Components/PrimaryButton.vue'
    import TextInput from '@/Components/TextInput.vue'

    const props = defineProps({
        list: Object,
    })

    const cartItems = computed(() => {
        return form.listItems.filter(item => item.is_picked === true)
    })

    const cartTotal = computed(() => {
        if (cartItems.length == 0) return 0

        return cartItems.value.reduce((total, item) => parseFloat(total) + (parseFloat(item.price) * parseInt(item.quantity)), 0).toFixed(2)
    })

    const hasCartItems = computed(() => {
        return cartItems.value.length > 0
    })

    const hasListItems = computed(() => {
        return form.listItems.length > 0
    })

    const hasSpendingLimit = computed(() => {
        return form.spendingLimit !== null && form.spendingLimit > 0
    })

    const isOverSpendingLimit = computed(() => {
        /* check for no spending limit set */
        if (form.spendingLimit == 0 || form.spendingLimit === null) return false

        return parseFloat(cartTotal.value) > parseFloat(form.spendingLimit)
    })

    const form = useForm({
        listItems: props.list !== null ? props.list.items : [],
        spendingLimit: props.list !== null ? props.list.spending_limit : null,
        ulid: props.list !== null ? props.list.id : null,
    })

    const addListItem = (item) => {
        /* push new item to the list */
        form.listItems.push(item)
    }

    const buyListItem = (idx) => {
        form.listItems[idx].is_picked = true
    }

    const removeCartItem = (idx) => {
        /* remove the item at the index */
        form.listItems.filter(item => item.is_picked === true)[idx].is_picked = false
    }

    const removeListItem = (idx) => {
        /* remove the item at the index */
        form.listItems.splice(idx, 1)
    }

    const save = () => {
        /* post shopping list */
        form.post(route('shopping.list.update'), {
            preserveScroll: true,
            onSuccess: (response) => {
                /* update form with saved results */
                form.ulid = response.props.list.id
                form.listItems = response.props.list.items
            }, 
        })
    }

    const updateListItem = (evt) => {
        console.log(evt)
    }

</script>

<template>

    <Head title="Shopping List" />

    <AuthenticatedLayout>

        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Shopping List
            </h2>
        </template>

        <div class="py-12">

            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

                <div class="p-4 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                        <div class="sm:p-4">

                            <div class="mb-4 flex justify-between items-center">

                                <h3 class="text-lg text-purple-800">Your List</h3>

                                <div>
                                    Spending Limit: <TextInput v-model="form.spendingLimit" class="w-24 px-2 py-1 text-right"/>
                                </div>

                            </div>

                            <List
                                v-model="form.listItems"
                                v-on:addItem="addListItem($event)"
                                v-on:buyItem="buyListItem($event)"
                                v-on:removeItem="removeListItem($event)"
                                v-on:updateItem="updateListItem($event)"
                                addable
                                editable
                                placeholder="No items on your shopping list!"
                                class="mt-2" />

                            <div v-if="hasListItems" class="mt-8 flex">
                                <PrimaryButton
                                    v-on:click="save()"
                                    class="bg-green-600">Save List</PrimaryButton>
                            </div>

                        </div>

                        <div class="sm:p-4 space-y-2">

                            <h3 class="text-lg text-purple-800">Shopping Basket</h3>

                            <div class="border border-gray-300 p-4 rounded-md">

                                <List 
                                    v-model="cartItems"
                                    v-on:removeItem="removeCartItem($event)" placeholder="Basket empty!" />

                                <div class="flex flex-wrap px-2 justify-between items-center">

                                    <div v-if="hasSpendingLimit" class="text-md">
                                        <span v-if="isOverSpendingLimit" class="text-yellow-700">You've gone over your spending limit!</span>
                                        <span v-if="!isOverSpendingLimit && hasCartItems" class="text-green-700">Excellent, you're under your spending limit!</span>
                                    </div>

                                    <div class="flex-1 text-right text-lg space-x-1">
                                        <span>Total Spend:</span>
                                        <span class="font-semibold">&pound {{ cartTotal }}</span>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
