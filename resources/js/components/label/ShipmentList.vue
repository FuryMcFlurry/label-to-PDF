<template>
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-semibold mb-4">Shipment Overview</h1>

        <!-- Check if shipments.value is an array and has items -->
        <div v-if="Array.isArray(shipments) && shipments.length > 0">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="px-4 py-2 font-semibold text-sm text-gray-600">Shipment Name</th>
                        <th class="px-4 py-2 font-semibold text-sm text-gray-600">Reference</th>
                        <th class="px-4 py-2 font-semibold text-sm text-gray-600">Status</th>
                        <th class="px-4 py-2 font-semibold text-sm text-gray-600">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="shipment in shipments" :key="shipment.id" class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2 text-sm">{{ shipment.product.name }}</td>
                        <td class="px-4 py-2 text-sm">{{ shipment.reference }}</td>
                        <td class="px-4 py-2 text-sm">{{ shipment.status }}</td>
                        <td class="px-4 py-2 text-sm">
                            <a :href="`/label/shipment/${shipment.id}`" class="px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-700">
                                View
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p v-else class="text-gray-600">No shipments found.</p>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const shipments = ref([]);

onMounted(async () => {
    try {
        const url = '/label/api/getList';
        const response = await axios.post(url, {}, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (response.status === 200) {
            shipments.value = response.data.data;  
            console.log("Shipments data:", shipments.value); 
        }
    } catch (error) {
        console.error("Error fetching shipments:", error);
    }
});
</script>
