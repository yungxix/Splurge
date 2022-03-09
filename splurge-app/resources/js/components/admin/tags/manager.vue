<template>
  <div v-if="loaded" class="flex flex-row items-center justify-end bg-white">
      <span class="text-gray-700 mr-8">Tags:</span>
      <Selector :tags="tags" :input-name="'tags'" @added="attach" @removed="detach" />
      <svg xmlns="http://www.w3.org/2000/svg" v-if="busy" class="mx-4 h-6 w-6 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
      <a v-if="indexUrl" :href="indexUrl" class="link ml-8">
          Manage list of tags
      </a>
  </div>
</template>

<script>
import Selector from './selector';
import axios from 'axios';
const Api = axios.create({
    withCredentials: true,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    transformRequest: (data, headers) => {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        if (data) {
            data['_token'] = token;
            return JSON.stringify(data);
        }
        const d = {
            _token: token
        };
        return JSON.stringify(d);
        
    }
});
export default {
    components: {Selector},
    props: {
        taggable: {
            type: Object,
        },
        indexUrl: String,
        baseURL: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            tags: [],
            showSelect: false,
            selectedTag: null,
            busy: false,
            errorMessage: null,
            loaded: false
        };
    },
    mounted() {
        this.fetch();
    },
    methods : {
        choose() {
            this.showSelect = true;
        },
        async attach(tag) {
            this.busy = true;
            try {
                await Api.post(`${this.baseURL}/${tag.id}/attach`, {
                    taggable: this.taggable
                });
                this.tags = this.tags.map((t) => {
                    if (t.id === tag.id) {
                        return {...t, attached: true, taggable: this.taggable};
                    }
                    return t;
                });
            } catch (ex) {
                this.errorMessage = ex.message;
            } finally {
                this.busy = false;
            }
        },
        async detach(tag) {
            this.busy = true;
            try {
                await Api.delete(`${this.baseURL}/${tag.id}/attach`, {
                    params: {
                        'taggable[id]': this.taggable.id,
                        'taggable[type]': this.taggable.type,
                    }
                });
                this.tags = this.tags.map((t) => {
                    if (t.id === tag.id) {
                        return {...t, attached: false, taggable: null};
                    }
                    return t;
                });
            } catch (ex) {
                this.errorMessage = ex.message;
            } finally {
                this.busy = false;
            }
        },
        async fetch() {
            const resp = await Api.get(this.baseURL, {
                params: {
                    attachment: '1',
                    attachments: '1',
                    type_name: this.taggable.type,
                    type_id: String(this.taggable.id)
                }
            });
            this.tags = resp.data.data;
            this.loaded = true;
            
        }
    },
    computed: {
        attached() {
            return this.tags.filter(x => x.attached === true);
        },
        available() {
            return this.tags.filter(x => x.attached !== true);
        }
    }
}
</script>

<style>

</style>