<template>
  <div>
    <div class="mb-8 flex justify-start max-w-3xl">
      <h1 class="font-bold text-3xl">
        <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('trips')">Trips</inertia-link>
        <span class="text-indigo-400 font-medium">/</span>
        {{ form.departure_location }} - {{ form.arrival_location }}
      </h1>
    </div>
    <div class="bg-white rounded-md shadow overflow-hidden max-w-3xl">
      <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
        <text-input v-model="form.departure_location" class="pr-6 pb-8 w-full lg:w-1/2" label="Departure" disabled />
        <text-input v-model="form.arrival_location" class="pr-6 pb-8 w-full lg:w-1/2" label="Arrival" disabled />
        <text-input v-model="form.departure_time" class="pr-6 pb-8 w-full lg:w-1/2" label="Departure Time" disabled />
        <text-input v-model="form.type" class="pr-6 pb-8 w-full lg:w-1/2" label="Type" disabled />
        <text-input v-model="form.price" class="pr-6 pb-8 w-full lg:w-1/2" label="Price" disabled />
      </div>
      <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end">
        <button :disabled="sending" class="flex items-center btn-indigo" @click="destroy">
          <div v-if="sending" class="btn-spinner mr-2" />
          Delete Trip
        </button>
      </div>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto mt-4">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="px-6 pt-6 pb-4">Airline Code</th>
          <th class="px-6 pt-6 pb-4">Airline Name</th>
          <th class="px-6 pt-6 pb-4">Number</th>
          <th class="px-6 pt-6 pb-4">Price</th>
          <th class="px-6 pt-6 pb-4">Departure</th>
          <th class="px-6 pt-6 pb-4">Departure Time</th>
          <th class="px-6 pt-6 pb-4">Arrival</th>
          <th class="px-6 pt-6 pb-4">Arrival Time</th>
        </tr>
        <tr v-for="flight in trip.flights" :key="flight.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="px-6 py-4 border-t">
            {{ flight.airline_code }}
          </td>
          <td class="px-6 py-4 border-t">
            {{ flight.airline_name }}
          </td>
          <td class="px-6 py-4 border-t">
            {{ flight.number }}
          </td>
          <td class="px-6 py-4 border-t">
            {{ flight.price }}
          </td>
          <td class="px-6 py-4 border-t">
            {{ flight.departure }}
          </td>
          <td class="px-6 py-4 border-t">
            {{ flight.departure_time }}
          </td>
          <td class="px-6 py-4 border-t">
            {{ flight.arrival }}
          </td>
          <td class="px-6 py-4 border-t">
            {{ flight.arrival_time }}
          </td>
        </tr>
        <tr v-if="trip.flights.length === 0">
          <td class="border-t px-6 py-4" colspan="8">No flights found.</td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'

export default {
  metaInfo() {
    return {
      title: `${this.form.departure_location} - ${this.form.arrival_location}`,
    }
  },
  components: {
    TextInput,
  },
  layout: Layout,
  props: {
    trip: Object,
  },
  data() {
    return {
      sending: false,
      form: {
        departure_location: this.trip.departure_location,
        departure_time: this.trip.departure_time,
        arrival_location: this.trip.arrival_location,
        flights: this.trip.flights,
        price: this.trip.price.toString(),
        type: this.trip.type,
      },
    }
  },
  methods: {
    destroy() {
      this.$inertia.delete(this.route('trips.destroy', this.trip.id), {
        onFinish: visit => {
          this.sending = false
        },
      })
    },
  },
}
</script>
