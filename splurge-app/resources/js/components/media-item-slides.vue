<template>
  <div class="relative">
    <h4 class="text-lg mb-4 ml-4">
        Recent Moments
    </h4>
    <vue-carousel :data="imageItems" :direction="direction" :controls="controllable">

    </vue-carousel>
  </div>
</template>
<script>

import VueCarousel from '@chenfengyuan/vue-carousel';

import {h} from 'vue';

export default {
  components: {
    VueCarousel
  },
  props: {
    items: {
      type: Array,
      required: true,
    },
    controllable: {
        type: Boolean,
        default: true
    },
    direction: {
      type: String,
      default: 'right'
    }
  },
  data() {
    return {
      vOptions: {
        autoplay: true,
      },
      vTransitions: ["fade", "cube", "book", "wave"],
      
    };
  },
  computed: {
    imageItems() {
      return this.items.map((item) => h('figure', {
        class: 'media-item-figure'
      }, [
        h('img', {
          src: item.image_url
        }),
        item.caption ? h('figcaption', [item.caption]) : h('span', [])
      ]))
    }
  },
};
</script> 