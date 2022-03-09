<template>
  <div class="flex flex-row flex-wrap items-center p-2 gap-4">
      <input type="hidden" :name="inputName" v-for="t in attachedTags" :key="t.id" :value="t.id" />
      <template v-for="tag in attachedTags" :key="tag.id">
        <span
          class="
            bg-gray-200
            inline-flex
            items-center
            rounded-md
            ring
            ring-gray-400
            px-4 py-2
            content-between
            justify-center
          "
        >
          <span v-text="tag.name"></span>
          <a @click="remove(tag)" class="ml-4 cursor-pointer hover:font-bold">
            &times;
          </a>
        </span>
      </template>
      <div
        v-if="availableTags.length > 0"
      >
        <Popover v-slot="{ open }" class="relative">
          <PopoverButton
            :class="open ? '' : 'text-opacity-90'"
            class="
              inline-flex
              items-center
              px-3
              py-2
              text-base
              font-medium
              text-white
              bg-pink-700
              rounded-md
              group
              hover:text-opacity-100
              focus:outline-none
              focus-visible:ring-2
              focus-visible:ring-white
              focus-visible:ring-opacity-75
            "
          >
            <span>Add tags</span>
            
            <svg xmlns="http://www.w3.org/2000/svg" v-if="!open" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" v-else class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
            </svg>
          </PopoverButton>
          <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="translate-y-1 opacity-0"
            enter-to-class="translate-y-0 opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="translate-y-0 opacity-100"
            leave-to-class="translate-y-1 opacity-0"
          >
            <PopoverPanel
              class="
                absolute
                z-50
                w-screen
                max-w-sm
                bg-white
                px-4
                mt-3
                transform
                -translate-x-1/2
                left-1/2
                sm:px-0
                lg:max-w-3xl
              "
            >
              <div
                class="
                  overflow-hidden
                  rounded-lg
                  shadow-lg
                  ring-1 ring-black ring-opacity-5
                "
              >
                <div class="p-4">
                    <input class="rounded-md shadow-sm border-gray-300 focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50" v-model="search" placeholder="Search" />
                </div>
                
                <div class="relative grid gap-8 bg-white p-7 lg:grid-cols-2">

                  <div
                    v-for="item in filteredTags"
                    :key="item.id"
                    :href="item.href"
                    class="
                      flex
                      items-center
                      p-2
                      -m-3
                      transition
                      duration-150
                      ease-in-out
                      rounded-lg
                      hover:bg-gray-50
                      
                    "
                  >
                    <div
                     class="grow text-gray-700"
                    >
                      {{ item.name }}
                    </div>
                    <div class="ml-4">
                      <a class="link" @click="add(item)">Add</a>
                    </div>
                  </div>
                </div>
               
              </div>
            </PopoverPanel>
          </transition>
        </Popover>
      </div>
    </div>
</template>

<script>
import { Popover, PopoverButton, PopoverPanel } from "@headlessui/vue";
export default {
  components: { Popover, PopoverButton, PopoverPanel },
  emits: ['added', 'removed'],
  props: {
    tags: {
      type: Array,
      required: true,
    },
    inputName: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      collection: this.tags,
      search: null,
    };
  },
  methods: {
    remove(tag) {
      this.collection = this.collection.map((t) => {
        if (t.id === tag.id) {
            
          return { ...t, attached: false };
        }
        return t;
      });
      this.$emit("removed", tag);
    },
    add(tag) {
      this.collection = this.collection.map((t) => {
        if (t.id === tag.id) {
          return { ...t, attached: true };
        }
        return t;
      });
      this.$emit("added", tag);
    },
  },
  computed: {
    attachedTags() {
      return this.collection.filter((x) => x.attached === true);
    },
    availableTags() {
      return this.collection.filter((x) => x.attached !== true);
    },
    filteredTags() {
      const t = this.search ? this.search.toLowerCase() : null;
      return this.availableTags.filter((x) => {
        if (!t) {
          return true;
        }
        return x.name.toLowerCase().indexOf(t) >= 0;
      });
    },
  },
};
</script>
