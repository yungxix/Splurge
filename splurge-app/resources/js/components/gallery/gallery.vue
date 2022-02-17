<template>
  <template v-for="group in groups" :key="group.id">
      <div class="mb-8">
          <div class="mb-4" v-if="group.heading">
              <h4 class="text-xl" v-text="group.heading"></h4>
          </div>
            <vue-carousel :direction="slideDirection" :data="mediaItemToComponents(group)">

            </vue-carousel>
           <div class="p-2 lg:p-4" v-if="group.content">
              <to-lines :text="group.content"></to-lines>
          </div>
      </div>
  </template>
</template>

<script>
import VueCarousel  from '@chenfengyuan/vue-carousel';
import SlideItemComponent from './gallery-item.vue';
import {uniqueId} from 'lodash';
import ToLines from '../shared/to-lines.vue';
import {h} from 'vue';
export default {
    components: {VueCarousel, ToLines},
    props: {
        slideDirection: { type: String, default: 'right'},
        groupSize: {
            type: Number,
            default: 4
        },
        gallery: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            groups: []
        };
    },
    mounted() {
        const groups = [];
        
        this.gallery.items.forEach((item) => {
            const size = item.media_items.length;
            if (size === 0) {
                return;
            }

            let offset = 0;
            
            while (offset < size) {
                groups.push({
                    items: item.media_items.slice(offset, offset + this.groupSize),
                    id: uniqueId('gal_'),
                    heading: offset === 0 ? item.heading : "",
                    content: offset === 0 ? item.content : ""
                });
                offset += this.groupSize;
            }
        });
       
        this.groups = groups;
    },
    methods: {
        mediaItemToComponent(item) {
            return h(SlideItemComponent, {
                heading: '',
                imageUrl: item.url
            });
        },
        mediaItemToComponents(group) {
            return group.items.map(i => this.mediaItemToComponent(i));
        }
    }
}
</script>

<style>

</style>