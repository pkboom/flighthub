<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">
      <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('trips')">Trips</inertia-link>
      <span class="text-indigo-400 font-medium">/</span> Create
    </h1>
    <div class="bg-white rounded-md shadow overflow-hidden max-w-3xl">
      <form @submit.prevent="search">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <select-input v-model="form.departure" :error="errors.departure" class="pr-6 pb-8 w-full lg:w-1/2" label="Departure">
            <template v-for="airport in airports">
              <option :key="airport.id" :value="airport.id">{{ airport.name }}</option>
            </template>
          </select-input>
          <select-input v-model="form.arrival" :error="errors.arrival" class="pr-6 pb-8 w-full lg:w-1/2" label="Arrival">
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
            <div v-if="errors.datetime" class="form-error">{{ errors.datetime }}</div>
          </div>
          <select-input v-model="form.type" :error="errors.type" class="pr-6 pb-8 w-full lg:w-1/2" label="Type">
            <option value="one_way">One Way</option>
            <option value="round_trip">Round Trip</option>
          </select-input>
        </div>
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end items-center">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Search Flights</loading-button>
        </div>
      </form>
    </div>
    <h1 class="my-4 font-semibold text-3xl">
      <span>Departure</span>
    </h1>
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
        <tr v-for="flight in leaving.data" :key="flight.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
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
            <button class="text-green-600 hover:underline" tabindex="-1" type="button" @click="add(flight)">Add to booking</button>
          </td>
        </tr>
        <tr v-if="leaving.data.length === 0">
          <td class="border-t px-6 py-4" colspan="8">No flights found.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="leaving.links" />
    <h1 class="my-4 font-semibold text-3xl">
      <span>Return</span>
    </h1>
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
        <tr v-for="flight in returning.data" :key="flight.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
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
            <button class="text-green-600 hover:underline" tabindex="-1" type="button" @click="add(flight)">Add to booking</button>
          </td>
        </tr>
        <tr v-if="returning.data.length === 0">
          <td class="border-t px-6 py-4" colspan="8">No flights found.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="returning.links" />
    <h1 class="my-4 font-semibold text-3xl">
      <span>Bookings</span>
    </h1>
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
        <tr v-for="booking in bookings" :key="booking.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="px-6 py-4 border-t">
            {{ booking.airline_code }}
          </td>
          <td class="px-6 py-4 border-t">
            {{ booking.airline_name }}
          </td>
          <td class="px-6 py-4 border-t">
            {{ booking.number }}
          </td>
          <td class="px-6 py-4 border-t">
            {{ booking.price }}
          </td>
          <td class="px-6 py-4 border-t">
            {{ booking.departure }}
          </td>
          <td class="px-6 py-4 border-t">
            {{ booking.departure_time }}
          </td>
          <td class="px-6 py-4 border-t">
            {{ booking.arrival }}
          </td>
          <td class="px-6 py-4 border-t">
            {{ booking.arrival_time }}
          </td>
          <td class="border-t w-px">
            <button class="text-green-600 hover:underline" tabindex="-1" type="button" @click="remove(booking)">Remove</button>
          </td>
        </tr>
        <tr v-if="bookings.length === 0">
          <td class="border-t px-6 py-4" colspan="8">No bookings found.</td>
        </tr>
      </table>
    </div>
    <div class="flex justify-end py-4">
      <button :disabled="sending" class="flex items-center btn-indigo" @click="book">
        <div v-if="sending" class="btn-spinner mr-2" />
        Book Flights
      </button>
    </div>
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
    leaving: Object,
    returning: Object,
    errors: Object,
  },
  remember: 'form',
  data() {
    return {
      sending: false,
      form: {
        departure: this.filters.departure,
        arrival: this.filters.arrival,
        datetime: this.filters.datetime,
        type: this.filters.type,
      },
      bookings: [],
    }
  },
  methods: {
    search() {
      this.$inertia.get(this.route('flights.search'), pickBy(this.form), { preserveState: true })
    },
    add(flight) {
      this.bookings.push(flight)
    },
    remove(flight) {
      this.bookings = this.bookings.filter(booking => booking.id != flight.id)
    },
    book() {
      this.sending = true

      this.$inertia.post(
        this.route('trips.store'),
        {
          bookings: this.bookings,
          type: this.form.type,
        },
        {
          onFinish: visit => {
            this.sending = false
          },
        },
      )
    },
  },
}
</script>
