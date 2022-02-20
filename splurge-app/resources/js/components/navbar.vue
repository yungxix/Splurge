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

            <div ref="userDowndown" class="hidden md:block" v-if="authenticated">
                    <dropdown width="188px" @close="showUserdropdown = false" :show="showUserdropdown">
                        <template  v-slot:trigger>
                              <button @click="toggleUserDropdownMenu()" class="flex items-center text-sm font-medium text-white hover:text-gray-200 hover:border-pink-300 focus:outline-none focus:text-pink-700 focus:border-pink-300 transition duration-150 ease-in-out">
                                <div>{{ username }}</div>
                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </template>

                        <template v-for="(item, index) in userDropdownItems" :key="index">
                            <a class="block mb-2 cursor-pointer p-2 text-gray-700 hover:font-bold"  @click="actOnUserItem($event, item)" v-text="item.text"></a>    
                        </template>
                      
                    </dropdown>
                    
                    
                    
            </div>

            <div ref="userDowndown" class="hidden md:block" v-else>
                <a :href="loginUrl" :class="getItemClass({active: false}, false)">Login</a>
            </div>
           

            <div class="-mr-2 flex md:hidden">
                <!-- Mobile menu button -->
                <button type="button" @click="toggleMobileMenu()" id="mobile-menu-trigger" aria-controls="mobile-menu" aria-expanded="false">
                
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
                 <div class="navbar-separator"></div>
                <template v-if="authenticated">
                    <p>Hi, {{ username }}</p>
                    <template v-for="(item, index) in userDropdownItems" :key="index">
                        <a :class="getItemClass(item, true)" @click="actOnUserItem($event, item)" v-text="item.text"></a>    
                    </template>
                </template>
                <a v-else :href="loginUrl" :class="getItemClass({}, true)">Login</a>
            </div>
        </div>
    </transition>
    <form ref="postForm" class="hidden h-0 w-0" :method="formMethod" :action="formUrl">
        <input type="hidden" name="_token" :value="xsrfToken" />
        <input type="hidden" name="_method" :value="formMethod" v-if="!isVirtualFormMethod()" />
        <template v-for="(value, key) in formParams" :key="key">
            <input type="hidden" :name="key" :value="value" />
        </template>
    </form>
  </div>
</template>

<script>

import Dropdown from './dropdown.vue';


const EMPTY_ARGS = {};

const CURRENT_ITEM_ARGS = {'aria-current': 'page'};

export default {
    components: {Dropdown},
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
        userDropdownItems: {
            type: Array,
            default() {
                return [];
            }
        },
        loginUrl: {
            type: String,
            default: '/login'
        }
    },
    data() {
        return {
            showMobileMenu: false,
            activeItemAttrs: CURRENT_ITEM_ARGS,
            inactiveItemAttrs: EMPTY_ARGS,
            showUserdropdown: false,
            xsrfToken: '',
            formMethod: 'POST',
            formUrl: '#',
            formParams: {},
            submitting: false
        };
    },
    mounted() {
       this.xsrfToken =  document.querySelector('meta[name="csrf-token"]').getAttribute('content');
       this.globalClickHander = (event) => {
           if (!this.$refs.userDowndown.contains(event.target)) {
               this.showUserdropdown = false;
           }
       };
       document.addEventListener('click', this.globalClickHander);
    },
    beforeUnmount() {
        document.removeEventListener('click', this.globalClickHander);
    },
    methods: {
        toggleMobileMenu() {
            this.showMobileMenu = !this.showMobileMenu;
        },
        toggleUserDropdownMenu() {
            this.showUserdropdown = !this.showUserdropdown;
        },
        isVirtualFormMethod() {
            return !((/POST|GET/i).test(this.formMethod));
        },
        getItemClass(item, mobileItem) {
            return {
                desktop: !mobileItem,
                active: item.active === true,
                mobile: mobileItem
            };
            // if (mobileItem) {
            //     return {
            //         'bg-pink-900 text-white block px-3 py-2 rounded-md text-base font-medium': item.active,
            //         'text-white hover:bg-pink-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium': !item.active
            //     };
            // }
            // return {
            //     'bg-pink-900 text-white px-3 py-2 rounded-md text-sm font-medium': item.active,
            //     'text-white hover:bg-pink-800 hover:text-white px-3 py-2 rounded-md text-sm font-medium': !item.active
            // };
        },
        getExtraItemAttributes(item) {
            if (item.active) {
                return this.activeItemAttrs
            }
            return this.inactiveItemAttrs;
        },
        actOnUserItem(event, item) {
            event.preventDefault();
            if (item.form) {
                if (this.submitting) {
                    return;
                }
                this.formMethod = item.form;
                this.formParams = item.params || {};
                this.formUrl = item.url;
                this.submitting = true;
                this.$nextTick(() => {
                    this.$refs.postForm.submit();
                });
            } else {
                window.location.href = item.url;
            }
        }
    }
}
</script>

<style>

</style>