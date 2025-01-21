<script setup>

    import { computed, ref } from 'vue'
    import _ from 'lodash'
    import DeleteIcon from '@/Components/Icons/Delete.vue'
    import TextInput from '@/Components/TextInput.vue'
    import PrimaryButton from '@/Components/PrimaryButton.vue'
    import SecondaryButton from '@/Components/SecondaryButton.vue'
    import ShoppingCartIcon from '@/Components/Icons/ShoppingCart.vue'
    import ShoppingCartRemoveIcon from '@/Components/Icons/ShoppingCartRemove.vue'

    const emit = defineEmits(['addItem', 'buyItem', 'removeItem'])

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
        isPurchased: false,
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
        emit('addItem', _.clone(product.value))

        /* reset form */
        Object.assign(product.value, {
            description: '',
            price: '0',
            quantity: '1',
            isPurchased: false,
        })

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

        <table v-if="hasItems" class="w-full mb-4 border-separate border-spacing-2">

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
                            'line-through text-gray-400': product.isPurchased && addable
                        }">

                        <td class="text-left">{{ product.description }}</td>
                        <td class="text-right">{{ product.price }}</td>
                        <td v-if="!addable" class="text-center">{{ product.quantity }}</td>
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
                class="flex-1" />

            <TextInput
                v-model="product.price" 
                v-on:keyup.enter="add()" 
                type="text"
                placeholder="Price"
                class="w-20 text-right" />

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