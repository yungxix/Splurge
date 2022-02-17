<template>
  <div>
      <div class="heading" v-if="heading">
          {{ heading }}
      </div>
        <img :class="imageClass" :src="imageUrl" :alt="heading" />
      <template v-if="description">
          <div v-show="showDescription">
              <to-lines :text="description"></to-lines>
          </div>
      </template>
  </div>
</template>

<script>
import ToLines from '../shared/to-lines.vue';
export default {
    components: {ToLines},
    props: {
       imageUrl: {
           type: String,
           required: true
       },
       heading: String,
       description: String,
       imageClass: String
    },
    data() {
        return {
            showDescription: false
        }
    },
    methods: {
        
    }
}
</script>
