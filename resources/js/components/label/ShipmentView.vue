<template>
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-semibold mb-4">Shipment Details</h1>
        <div v-if="!loadBool">
            <p class="text-gray-600">Loading...</p>
        </div>

        <div v-else>
            <!-- Shipment Overview -->
            <div class="bg-white border border-gray-300 rounded-lg shadow-md p-4 mb-6">
                  <h2 class="text-xl font-semibold text-gray-700 mb-2">Shipment Overview</h2>
                  <div class="grid grid-cols-2 gap-4">
                     <div><strong>Reference:</strong> {{ shipment.reference }}</div>
                     <div><strong>Status:</strong> {{ shipment.status }}</div>
                     <div><strong>Barcode:</strong> {{ shipment.barcode }}</div>
                     <div><strong>Weight:</strong> {{ shipment.weight }} g</div>
                     <div><strong>Tracking URL:</strong>
                        <a :href="shipment.tracking_url" class="text-blue-500 hover:underline" target="_blank">
                              Track Shipment
                        </a>
                     </div>
                     <div><strong>PDF: </strong>
                        <button @click="downloadPdf" class="px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-700" target="_blank">
                              Download
                        </button>
                     </div>
                  </div>
            </div>

            <!-- Delivery Contact Details -->
            <div class="bg-white border border-gray-300 rounded-lg shadow-md p-4 mb-6">
                  <h2 class="text-xl font-semibold text-gray-700 mb-2">Delivery Contact</h2>
                  <p><strong>Name:</strong> {{ shipment.delivery_contact.name }}</p>
                  <p><strong>Address:</strong> {{ shipment.delivery_contact.street }} {{ shipment.delivery_contact.housenumber }}, {{ shipment.delivery_contact.postalcode }}, {{ shipment.delivery_contact.locality }}, {{ shipment.delivery_contact.country }}</p>
            </div>

            <!-- Shipment Events -->
            <div class="bg-white border border-gray-300 rounded-lg shadow-md p-4">
                  <h2 class="text-xl font-semibold text-gray-700 mb-2">Shipment Events</h2>
                  <ul class="divide-y divide-gray-200">
                     <li v-for="event in shipment.events" :key="event._id" class="py-2">
                        <p><strong>Message:</strong> {{ event.message }}</p>
                        <p><strong>Creator:</strong> {{ event.creator }}</p>
                        <p><strong>Date:</strong> {{ formatDate(event.created.date) }}</p>
                     </li>
                  </ul>
                  
            </div>
        </div>
        
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const shipmentId = document.getElementById('label-view').getAttribute('data-shipment-id');
const shipment = ref([]);  
const loadBool = ref(false);

function formatDate(dateString) {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('en-GB', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    }).format(date);
}


onMounted(async () => {
    try {
        const url = '/label/api/getShipment';
        const response = await axios.post(url, { id: shipmentId }, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (response.status === 200) {
         shipment.value = response.data.data; 
         loadBool.value = true;
        }
    } catch (error) {
        console.error("Error fetching shipment:", error);
    }
});


const downloadPdf = async () => {
    try {
        const response = await axios.post('/label/generate-pdf', { shipment: shipment.value }, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            responseType: 'blob'  // Important for handling binary data as PDF
        });
        
        // Create a link to download the PDF
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', shipment.value.delivery_contact.name + ' - shipment.pdf');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    } catch (error) {
        console.error("Error generating PDF:", error);
    }
};

</script>
