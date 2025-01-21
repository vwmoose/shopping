<script setup>

    import { computed, ref } from 'vue'
    import DeleteIcon from '@/Components/Icons/Delete.vue'
    import TextInput from '@/Components/TextInput.vue'
    import PrimaryButton from '@/Components/PrimaryButton.vue'
    import SecondaryButton from '@/Components/SecondaryButton.vue'
    import ShoppingCartIcon from '@/Components/Icons/ShoppingCart.vue'
    import ShoppingCartRemoveIcon from '@/Components/Icons/ShoppingCartRemove.vue'

    const emit = defineEmits(['addItem', 'buyItem', 'removeItem', 'updateItem'])

    const props = defineProps({
        addable: {
            type: Boolean,
            default: false,
        },
        editable: {
            type: Boolean,
            default: false,
        },
        modelValue: {
            type: Array,
            default: [],
        },
        placeholder: {
            type: String,
            default: ''
        },
        type: {
            type: String,
            default: 'list'
        }
    })

    const hasErrors = ref(false)
    const product = ref({
        description: '',
        price: '0.00',
        quantity: '1',
        is_picked: false,
    })

    const refDescription = ref(null)

    const hasItems = computed(() => {
        return props.modelValue.length > 0
    })

    const add = () => {
        /* flag error */
        hasErrors.value = false

        /* validate submission */
        if (isNaN(product.value.price) || isNaN(product.value.quantity) || product.value.price == 0) {
            /* flag error */
            hasErrors.value = true

            return
        }
        

        /* float the number */
        product.value.price = parseFloat(product.value.price).toFixed(2)

        /* emit add item event */
        emit('addItem', { ...product.value })

        /* reset form */
        Object.assign(product.value, {
            description: '',
            price: '0',
            quantity: '1',
            is_picked: false,
        })

        /* set focus to description field */
        refDescription.value.focus()
    }

    const buy = (idx) => {
        /* emit buy item event */
        emit('buyItem', idx)
    }

    const remove = (idx) => {
        /* emit remove item event */
        emit('removeItem', idx)
    }

</script>

<template>

    <div class="space-y-2">

        <table v-if="hasItems" class="w-full border-separate border-spacing-2">

            <thead>
                <tr>
                    <th class="text-sm text-left">Description</th>
                    <th class="text-sm text-right">Price (each)</th>
                    <th v-if="!addable" class="text-sm text-center">Quantity</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>

            <tbody>

                <template v-for="(product, idx) in modelValue" :key="`list_item_${idx}`">
    
                    <tr :class="{
                            'line-through text-gray-400': product.is_picked && addable
                        }">

                        <td class="text-left">{{ product.description }}</td>
                        <td class="text-right">{{ product.price }}</td>
                        <td v-if="!addable" class="flex justify-center">
                            <div class="grid w-full max-w-16 grid-cols-1">
                                <select 
                                    v-model="product.quantity"
                                    :name="`quantity-${idx}`" 
                                    class="col-start-1 row-start-1 appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                </select>
                            </div>
                        </td>
                        <td class="text-right space-x-1">
                            <SecondaryButton v-if="addable" v-on:click="buy(idx)">
                                <ShoppingCartIcon class="w-4 h-4 fill-current" />
                            </SecondaryButton>
                            <SecondaryButton v-on:click="remove(idx)" class="text-red-500">
                                <DeleteIcon v-if="editable" class="w-4 h-4 fill-current" />
                                <ShoppingCartRemoveIcon v-else class="w-4 h-4 fill-current" />
                            </SecondaryButton>
                        </td>

                    </tr>
    
                </template>

            </tbody>

        </table>

        <div v-if="props.addable" class="flex space-x-2">

            <TextInput 
                ref="refDescription"
                v-model="product.description"
                type="text"
                placeholder="description"
                class="flex-1 px-2 py-1" />

            <TextInput
                v-model="product.price" 
                v-on:keyup.enter="add()" 
                type="text"
                placeholder="Price"
                class="w-20 px-2 py-1 text-right" />

            <!-- <TextInput v-model="product.quantity" v-on:keyup.enter="add()" type="text" placeholder="Quantity" class="w-12 text-center"/> -->
            <PrimaryButton
                v-on:click="add()">
                Add
            </PrimaryButton>

        </div>

        <div v-if="hasErrors" class="py-2 text-red-500">
            Please ensure you enter valid data, price cannot be zero and both quantity and price require numberical values!
        </div>
        
        <div v-if="!hasItems & !hasErrors" class="text-gray-500">
            {{ placeholder }}
        </div>
    </div>

</template>