<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">
      <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('trips')">Trips</inertia-link>
      <span class="text-indigo-400 font-medium">/</span> Create
    </h1>
    <div class="bg-white rounded-md shadow overflow-hidden max-w-3xl">
      <form @submit.prevent="search">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <select-input v-model="form.departure" class="pr-6 pb-8 w-full lg:w-1/2" label="Departure">
            <template v-for="airport in airports">
              <option :key="airport.id" :value="airport.id">{{ airport.name }}</option>
            </template>
          </select-input>
          <select-input v-model="form.arrival" class="pr-6 pb-8 w-full lg:w-1/2" label="Arrival">
            <template v-for="airport in airports">
              <option :key="airport.id" :value="airport.id">{{ airport.name }}</option>
            </template>
          </select-input>
          <div class="pr-6 pb-8 w-full lg:w-1/2">
            <label class="form-label" for="datetime">Date & Time:</label>
            <datetime
              id="datetime"
              v-model="form.datetime"
              type="datetime"
              :hour-step="2"
              :minute-step="15"
              class="border-2 px-2 py-2.5 rounded"
            />
          </div>
        </div>
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end items-center">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Search Flights</loading-button>
        </div>
      </form>
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
          <th class="px-6 pt-6 pb-4" colspan="2">Arrival Time</th>
        </tr>
        <tr v-for="flight in flights.data" :key="flight.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
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
          <td class="border-t w-px">
            <button class="text-green-600 hover:underline" tabindex="-1" type="button" @click="addToTrip(flight)">Add to trip</button>
          </td>
        </tr>
        <tr v-if="flights.data.length === 0">
          <td class="border-t px-6 py-4" colspan="8">No flights found.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="flights.links" />
  </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'
import { Datetime } from 'vue-datetime'
import 'vue-datetime/dist/vue-datetime.css'
import pickBy from 'lodash/pickBy'
import Pagination from '@/Shared/Pagination'

export default {
  metaInfo: { title: 'Create Trip' },
  components: {
    LoadingButton,
    SelectInput,
    Datetime,
    Pagination,
  },
  layout: Layout,
  props: {
    filters: Object,
    airports: Array,
    flights: Object,
  },
  remember: 'form',
  data() {
    return {
      form: {
        departure: this.filters.departure,
        arrival: this.filters.arrival,
        datetime: this.filters.datetime,
      },
    }
  },
  methods: {
    search() {
      console.log(pickBy(this.form))
      this.$inertia.get(this.route('trips.create'), pickBy(this.form), { preserveState: true })
    },
    addToTrip(flight) {
      console.log(flight)
    },
    store() {
      this.form.post(this.route('trips.create'))
    },
  },
}
</script>
