<template>
  <div>
    <div class="mb-4">
      <inertia-link class="flex items-center group py-3" :href="route('trips')">
        <div :class="isUrl('trips') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Trips</div>
      </inertia-link>
    </div>
  </div>
</template>

<script>
import Icon from '@/Shared/Icon'

export default {
  components: {
    Icon,
  },
  methods: {
    isUrl(...urls) {
      let currentUrl = this.$page.url.substr(1)
      if (urls[0] === '') {
        return currentUrl === ''
      }
      return urls.filter(url => currentUrl.startsWith(url)).length
    },
  },
}
</script>
