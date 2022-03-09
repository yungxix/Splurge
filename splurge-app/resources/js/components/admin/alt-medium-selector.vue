<template>
  <div>
      <div v-if="'upload' === preferredInput">
          <div class="mb-4">
              <input type="file" :name="fileInputName" accept="image/*" />
              <button type="button" class="btn" @click="browse()">
                  Use existing
              </button>
          </div>
          <div class="" v-if="captionInputName">
              <label :for="captionInputName" class="block mb-2 text-gray-800">
                  Name/caption of image
              </label>
              <input type="text" :name="captionInputName" class="control w-full" v-model="caption" />
          </div>
      </div>

      <div v-else>
          <div v-if="selectedMediaOwner">
              <input type="hidden" :name="mediumInputName" :value="selectedMediaOwner.id" />
              <div class="">
                  <figure>
                      <img :src="selectedMediaOwner.url" />
                      <figcaption>
                          {{ selectedMediaOwner.name }}
                      </figcaption>
                  </figure>
              </div>
              <button class="btn" @click="openBrowser()" type="button">
                  Change it
              </button>
          </div>
          <div v-else-if="!showBrowser">
            <button type="button" class="btn block mr-2" @click="preferredInput = 'upload'">
                Upload new file
            </button>

            <button  type="button" class="btn" @click="openBrowser()">
                Select another picture
            </button>
          </div>
          
      </div>
  </div>
   
  <TransitionRoot :show="showBrowser" as="template">
    <Dialog @close="closeBrowser">
      <div class="fixed inset-0 z-10 overflow-y-auto">
        <div
          class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0"
        >
          <TransitionChild
            as="template"
            enter="ease-out duration-300"
            enterFrom="opacity-0"
            enterTo="opacity-75"
            leave="ease-in duration-200"
            leaveFrom="opacity-75"
            leaveTo="opacity-0"
            entered="opacity-75"
          >
            <DialogOverlay className="fixed inset-0 bg-gray-500 transition-opacity" />
          </TransitionChild>

          <TransitionChild
            enter="ease-out transform duration-300"
            enterFrom="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enterTo="opacity-100 translate-y-0 sm:scale-100"
            leave="ease-in transform duration-200"
            leaveFrom="opacity-100 translate-y-0 sm:scale-100"
            leaveTo="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">
              &#8203;
            </span>
            <div
              class="inline-block transform w-full md:w-4/5  overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle"
            >
              <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="">
                  
                  <div class="mt-3">
                    <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                      Browse for a Picture
                    </DialogTitle>
                    <div class="mt-2">
                        <div class="mb-2 w-full flex flex-row rounded-md overflow-clip">
                            <input type="search" v-model="search" @input="handleSearch" placeholder="Search" class="rounded-l-md border-purple-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" size="24" />
                            <div class="rounded-r-md px-2 bg-slate-500 text-white inline-flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                      <div class="gap-4 grid grid-cols-2 md:grid-cols-3">
                           
                          <template v-for="item in mediumList.data" :key="item.id">
                              <a @click="select(item)" class="cursor-pointer block rounded-md hover:ring ring-purple-500 focus:ring">
                                  <figure>
                                      <img :src="item.url" />
                                      <figcaption>
                                          {{ item.name }}
                                      </figcaption>
                                  </figure>
                              </a>
                          </template>
                          
                      </div>
                      <svg xmlns="http://www.w3.org/2000/svg" v-if="'loading' === status" class="animate-spin mx-auto h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                      <div class="flex flex-row items-center mt-4 content-between justify-between" v-if="mediumList.meta.last_page > 1">
                            

                              <a @click="fetchOffset(-1)" class="cursor-pointer text-purple-500 hover:text-purple-600 " :class="{'opacity-40': !hasPreviousPage, 'opacity-100': hasPreviousPage}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-current current-text" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                              </a>

                              <a @click="fetchOffset(1)" class="cursor-pointer  text-purple-500 hover:text-purple-600 " :class="{'opacity-40': !hasMorePages, 'opacity-100': hasMorePages}">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>

                              </a>
                          </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script>
import axios from 'axios';
import {throttle} from 'lodash';

 import {
    Dialog,
    DialogOverlay,
    DialogTitle,
    DialogDescription,
    TransitionRoot,
    TransitionChild
  } from "@headlessui/vue";

export default {
    components: { Dialog,
    DialogOverlay,
    DialogTitle,
    DialogDescription,
    TransitionRoot,
    TransitionChild},
    props: {
        fileInputName: {
            type: String,
            required: true
        },
        captionInputName: {
            type: String
        },
        mediumInputName: {
            type: String,
            required: true
        }
    },
    created() {
        this.throttledSearch = throttle(() => {
            this.fetchImpl(1);
        }, 300);
    },
    data() {
        return {
            showBrowser: false,
            mediumList: {
                data: [],
                links: {
                    next: null,
                    prev: null
                },
                meta: {
                    current_page: 0,
                    from: null,
                    last_page: null,
                    path: null,
                    per_page: 15,
                    to: null,
                    total: 0
                }
            },
            status: 'pending',
            selectedMediaOwner: null,
            preferredInput: 'upload',
            caption: null,
            search: ''
        };
    },
    methods: {
        closeBrowser() {
            this.showBrowser = false;
        },
        openBrowser() {
            this.showBrowser = true;
            if ('pending' === this.status) {
                this.fetchImpl(1);
            }
        },
        select(item) {
            this.selectedMediaOwner = item;
            this.showBrowser = false;
        },
        browse() {
            this.preferredInput = 'media';
            this.openBrowser();
        },

        fetchOffset(offset) {
            const page = this.mediumList.meta.current_page + offset;
            if (page < 1 || page > this.mediumList.meta.last_page) {
                return;
            }
            this.fetchImpl(page);
        },

        handleSearch() {
            this.throttledSearch();
        },

        async fetchImpl(page) {
            this.status = 'loading';
            try {
                
                const response = await axios.get('/admin/media', {
                    params: {
                        page,
                        q: this.search
                    },
                    headers: {
                        'Accept': 'application/json'
                    }
                });    
                this.mediumList = response.data;
                this.status = 'loaded';
            } catch (error) {
                this.status = 'error';
            }
        }
    },
    computed: {
        hasMorePages() {
            return this.mediumList.meta.current_page < this.mediumList.meta.last_page;
        },
        hasPreviousPage() {
            return this.mediumList.meta.current_page > 1;
        }
    }
}
</script>

<style>

</style>