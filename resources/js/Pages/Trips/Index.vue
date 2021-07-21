<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">Trips</h1>
    <div class="mb-6 flex justify-end items-center">
      <inertia-link class="btn-indigo" :href="route('trips.create')">
        <span>Create</span>
        <span class="hidden md:inline">Trip</span>
      </inertia-link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="px-6 pt-6 pb-4">Type</th>
          <th class="px-6 pt-6 pb-4">Departure</th>
          <th class="px-6 pt-6 pb-4">Arrival</th>
          <th class="px-6 pt-6 pb-4">Departure Time</th>
          <th class="px-6 pt-6 pb-4" colspan="2">Price</th>
        </tr>
        <tr v-for="trip in trips.data" :key="trip.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center focus:text-indigo-500" :href="route('trips.edit', trip.id)">
              {{ trip.type }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center" :href="route('trips.edit', trip.id)" tabindex="-1">
              {{ trip.departure_location }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center" :href="route('trips.edit', trip.id)" tabindex="-1">
              {{ trip.arrival_location }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center" :href="route('trips.edit', trip.id)" tabindex="-1">
              {{ trip.departure_time }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center" :href="route('trips.edit', trip.id)" tabindex="-1">
              {{ trip.price }}
            </inertia-link>
          </td>
          <td class="border-t w-px">
            <inertia-link class="px-4 flex items-center" :href="route('trips.edit', trip.id)" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </inertia-link>
          </td>
        </tr>
        <tr v-if="trips.length === 0">
          <td class="border-t px-6 py-4" colspan="6">No trips found.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="trips.links" />
  </div>
</template>

<script>
import Icon from '@/Shared/Icon'
import pickBy from 'lodash/pickBy'
import Layout from '@/Shared/Layout'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import Pagination from '@/Shared/Pagination'

export default {
  metaInfo: { title: 'trips' },
  components: {
    Icon,
    Pagination,
  },
  layout: Layout,
  props: {
    filters: Object,
    trips: Object,
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function() {
        this.$inertia.get(this.route('trips'), pickBy(this.form), { preserveState: true })
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
  },
}
</script>
