<template>
  <vue-carousel :data="pageComponents"> </vue-carousel>
</template>

<script>
import { sortBy, uniqueId } from "lodash";

import ToLines from "../shared/to-lines.vue";

import VueCarousel from "@chenfengyuan/vue-carousel";

import { h } from "vue";

const PageComponent = {
  components: { ToLines },
  props: {
    page: {
      type: Object,
      required: true,
    },
    gridClass: {
      type: String,
      required: true,
    },
  },
  template: `
        <div class="collage-content">
            <div class="heading" v-if="page.heading">
                    <h4 v-text="page.heading"></h4>
            </div>
            <div :class="gridClass">
                <template  v-for="(item, index) in page.group" :key="item.id">
                     <figure :class="gridItemClass(item)">
                        <img :alt="item.name" :src="item.url" />
                        <figcaption v-text="item.name" class="text-white"></figcaption>
                    </figure>
                </template>
            </div>
            
            <div class="footer" v-if="page.content">
                <to-lines :text="page.content"></to-lines>
            </div>
        </div>
    `,
  methods: {
    gridItemClass(item) {
      const defaultClass = `block ${item.extraClass}`;
      if (item.span > 1) {
        return `${defaultClass} col-span-${item.span} text-center`;
      }
      return defaultClass;
    },
  },
};


const deriveItemExtraClass = (index, gridSize) => {
    const gridIndex = index % gridSize;
    if (gridIndex === 0) {
        return '';
    }
    if (gridIndex === gridSize - 1) {
        return 'ml-auto';
    }

    return 'mx-auto';
};
export default {
  components: { VueCarousel },
  props: {
    gallery: {
      type: Object,
      required: true,
    },
    groupSize: {
      type: Number,
      default: 5,
    },
  },
  mounted() {
    this.reArrange();
    this.resizeHandler = this.reArrange.bind(this);
    window.addEventListener("resize", this.resizeHandler);
  },
  unmounted() {
    window.removeEventListener("resize", this.resizeHandler);
  },
  data() {
    return {
      pages: [],
      gridClass: "grid grid-cols-auto gap-2 md:grap-4",
    };
  },
  methods: {
    getSortedMediaItems(item) {
      return sortBy(item.media_items, (item) => {
        if (item.image_options) {
          const max = Math.max(
            item.image_options.width,
            item.image_options.height
          );
          return -max;
        }
        return 0;
      });
    },
    reArrange() {
      const bounds = this.$el.getBoundingClientRect();

      const pages = [];

      const gridSize = this.calculateGridSize(bounds.width);

      this.gridClass = `grid grid-cols-${gridSize} gap-2 md:grap-4`;

      this.gallery.items.forEach((item, itemIndex) => {
        let offset = 0;
        const len = item.media_items.length;

        while (offset < len) {
          const chunk = item.media_items.slice(offset, offset + this.groupSize);
          const group = chunk.map((media, mediaIndex) => {
            if (media.image_options.width >= bounds.width) {
              return {
                url: media.url,
                span: gridSize,
                name: media.name,
                id: media.id,
                index: mediaIndex,
                extraClass: deriveItemExtraClass(mediaIndex, gridSize)
              };
            }
            const localSpan = (
              100 * (media.image_options.width / bounds.width)
            ) > 60 ? 2 : 1;
            return {
              url: media.url,
              span: localSpan,
              name: media.name,
              id: media.id,
              index: mediaIndex,
              extraClass: deriveItemExtraClass(mediaIndex, gridSize)
            };
          });
          pages.push({
            heading: item.heading,
            content: item.content,
            group,
            id: uniqueId('slide_page_')
          });
          offset += this.groupSize;
        }
      });

      this.pages = pages;
    },
    calculateGridSize(width) {
        if (width > 1800) {
            return 4;
        }
      if (width > 1024) {
        return 3;
      }
      if (width > 706) {
        return 2;
      }
      return 1;
    },
  },
  computed: {
    pageComponents() {
      return this.pages.map((page, index) =>
        h(PageComponent, {
          page,
          gridClass: this.gridClass,
          index,
        })
      );
    },
  },
};
</script>

<style>
</style>