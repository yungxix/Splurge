<template>
  <div class="w-full">
      <div class="w-full mx-auto py-2 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center justify-between">
                <div class="flex-shrink-0">
                    <a :href="logoUrl">
                        <img class="h-12 w-12" :src="logo" alt="Splurge Logo">
                    </a>
                </div>
                <div class="hidden md:block grow">
                    <div class="ml-10 flex items-baseline w-full justify-end content-end space-x-4 justify-items-end">
                        <template v-for="(item, index) in items" :key="index">
                            <a :href="item.url" :class="getItemClass(item, false)" v-bind="getExtraItemAttributes(item)" v-text="item.text"></a>    
                        </template>
                    </div>
                </div>
            </div>
           

            <div class="-mr-2 flex md:hidden">
                <!-- Mobile menu button -->
                <button type="button" @click="toggleMobileMenu()" id="mobile-menu-trigger" class="bg-pink-800 inline-flex items-center justify-center p-2 rounded-md text-pink-400 hover:text-white hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-pink-800 focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">
                
                <span class="sr-only">Open main menu</span>
                <!--
                    Heroicon name: outline/menu

                    Menu open: "hidden", Menu closed: "block"
                -->
                <svg v-if="!showMobileMenu" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <!--
                    Heroicon name: outline/x

                    Menu open: "block", Menu closed: "hidden"
                -->
                <svg v-if="showMobileMenu" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state. -->
    <transition  enter-active-class="origin-top-left duration-200 ease-out" 
    enter-from-class="opacity-0 scale-y-50" 
    enter-to-class="opacity-100 scale-y-100" 
    leave-active-class="origin-top-left duration-200 ease-in" 
    leave-from-class="opacity-100 scale-y-100" 
    leave-to-class="opacity-0 scale-y-50">
        <div v-if="showMobileMenu" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <template v-for="(item, index) in items" :key="`${index}_mobile_item`">
                    <a :href="item.url" :class="getItemClass(item, true)" v-bind="getExtraItemAttributes(item)" v-text="item.text"></a>    
                </template>
            </div>
        </div>
    </transition>
    
  </div>
</template>

<script>



const EMPTY_ARGS = {};

const CURRENT_ITEM_ARGS = {'aria-current': 'page'};

export default {
    props: {
        logo: {
            type: String,
            required: true
        },
        logoUrl: {
            type: String,
            required: true
        },
        authenticated: {
            type: Boolean,
            required: true
        },
        username: String,
        items: {
            type: Array,
            required: true
        },

    },
    data() {
        return {
            showMobileMenu: false,
            activeItemAttrs: CURRENT_ITEM_ARGS,
            inactiveItemAttrs: EMPTY_ARGS
        };
    },
    methods: {
        toggleMobileMenu() {
            this.showMobileMenu = !this.showMobileMenu;
        },
        getItemClass(item, mobileItem) {
            if (mobileItem) {
                return {
                    'bg-pink-900 text-white block px-3 py-2 rounded-md text-base font-medium': item.active,
                    'text-white hover:bg-pink-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium': !item.active
                };
            }
            return {
                'text-bold': true,
                'bg-pink-900 text-white px-3 py-2 rounded-md text-sm font-medium': item.active,
                'text-white hover:bg-pink-800 hover:text-white px-3 py-2 rounded-md text-sm font-medium': !item.active
            };
        },
        getExtraItemAttributes(item) {
            if (item.active) {
                return this.activeItemAttrs
            }
            return this.inactiveItemAttrs;
        }
    }
}
</script>

<style>

</style>