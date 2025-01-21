<script setup>

    import { computed, ref } from 'vue'
    import { Head } from '@inertiajs/vue3'
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
    import List from '@/Components/Shopping/List.vue'

    const cartItems = computed(() => {
        return listItems.value.filter(item => item.isPurchased === true)
    })

    const cartTotal = computed(() => {
        if (cartItems.length == 0) return 0

        return cartItems.value.reduce((total, item) => parseFloat(total) + parseFloat(item.price), 0).toFixed(2)
    })

    const listItems = ref([])

    const addListItem = (item) => {
        /* push new item to the list */
        listItems.value.push(item)
    }

    const buyListItem = (idx) => {
        listItems.value[idx].isPurchased = true
    }

    const removeCartItem = (idx) => {
        /* remove the item at the index */
        listItems.value.filter(item => item.isPurchased === true)[idx].isPurchased = false
    }

    const removeListItem = (idx) => {
        /* remove the item at the index */
        listItems.value.splice(idx, 1)
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
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div class="p-4 space-y-2">

                            <h3 class="text-purple-800">Your Shopping List</h3>

                            <div class="xborder border-gray-300 rounded-md">
                                <List
                                    v-model="listItems"
                                    v-on:addItem="addListItem($event)"
                                    v-on:buyItem="buyListItem($event)"
                                    v-on:removeItem="removeListItem($event)"
                                    addable
                                    editable
                                    placeholder="No items on your shopping list!" />
                            </div>

                        </div>

                        <div class="p-4 space-y-2">

                            <h3 class="text-purple-800">Shopping Basket</h3>

                            <div class="border border-gray-300 p-4 rounded-md">

                                <List 
                                    v-model="cartItems"
                                    v-on:removeItem="removeCartItem($event)" placeholder="Basket empty!" />

                                <div class="flex px-2 justify-end space-x-2 text-lg">
                                    <span>Total Spend:</span> <span class="font-semibold">&pound {{ cartTotal }}</span>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
