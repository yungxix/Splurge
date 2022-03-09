<template>
  <div class="flex flex-col">
    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
        <div class="shadow overflow-hidden border-b border-pink-200 sm:rounded-lg">
            <p v-if="errorMessage" class="text-center p-4 text-red-700" v-text="errorMessage">

            </p>
          <table class="min-w-full divide-y divide-pink-200">
            <thead class="bg-pink-100">
              <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    #
                 </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Name
                 </th>
                 <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        
                 </th>      
              </tr>
              
            </thead>
            <tbody class="bg-white divide-y divide-pink-200">
                <tr>
                  <td colspan="3">
                      <div class="w-full flex flex-row items-center px-6 py-4">
                          <input :disabled="busy" @keypress.enter="save()" ref="text_field" v-model="editing.name" :placeholder="`${editing.id > 0 ? 'Edit tag' : 'New tag'}`" class="grow rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                          <div v-if="!busy" class="ml-4 flex flex-row items-center justify-end gap-2">
                              <a  @click="save()" v-if="editing.name" class="link text-sm">
                                  Save <template v-if="editing.index >= 0">
                                      <span>tag #{{ editing.index + 1 }}</span>
                                  </template>
                              </a>
                              <a @click="cancelEdit()" class="link text-sm">
                                  Cancel
                              </a>
                          </div>
                          <svg v-if="busy" xmlns="http://www.w3.org/2000/svg" class="ml-4 h-6 w-6 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                          </svg>
                      </div>
                  </td>
              </tr>
                <tr v-for="(tag, index) in collection" :key="tag.id">
                    <td class="px-6 py-4 whitespace-nowrap">#{{ index + 1 }}</td>
                    <td :class="{'font-bold': tag.id === editing.id}" class="px-6 py-4 whitespace-nowrap">
                        {{ tag.name }}
                        <svg v-if="tag.id === editing.id" xmlns="http://www.w3.org/2000/svg" class="ml-8 h-6 w-6 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </td>
                    <td  class="text-right px-6 py-4 whitespace-nowrap">
                        <span v-if="!busy">
                            <a @click="edit(tag, index)" class="link">Edit</a>
                            &nbsp;&nbsp;
                            <a @click="remove(tag)" class="link">Delete</a>
                        </span>
                    </td>
                </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
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
    props: {
        tags: {
            type: Array,
            required: true
        },
        baseURL: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            editing: {
                id: -1,
                name: '',
                index: -1
            },
            busy: false,
            collection: this.tags,
            errorMessage: null
        };
    },
    methods: {
        async save() {
            if (this.busy || !this.editing.name) {
                return;
            }
            this.busy = true;
            this.errorMessage = null;
            try {
                if (this.editing.id > 0) {
                    const resp = await Api({
                        method: 'PATCH',
                        url: `${this.baseURL}/${this.editing.id}`,
                        data: {name: this.editing.name}
                    });
                    this.collection = this.collection.map((t) => t.id === resp.data.data.id ? resp.data.data : t);
                } else {
                    const resp = await Api({
                        method: 'post',
                        url: this.baseURL,
                        data: {name: this.editing.name}
                    });
                    this.collection = [resp.data.data, ...this.collection];
                }
                this.editing = {id: 0, name: '', index: -1};
            } catch (ex) {
                this.errorMessage = ex.message;
            } finally {
                this.busy = false;
            } 
            
        },
        edit(tag, index) {
            if (this.busy) {
                return;
            }
            this.editing = {
                id: tag ? tag.id : 0,
                name: tag.name,
                index: tag ? index : -1
            };
            this.$refs.text_field.focus();
        },
        async remove(tag) {
            if (this.busy) {
                return;
            }
            this.busy = true;
            this.errorMessage = null;
            try {
                await Api.delete(`${this.baseURL}/${tag.id}`);
                this.collection = this.collection.filter(t => t.id !== tag.id);
            } catch(ex) {
                this.errorMessage = ex.message;
            } finally {
                this.busy = false;
            }
            
        },
        cancelEdit() {
            if (this.busy) {
                return;
            }
            this.editing = {id: -1, name: '', index: -1};
            this.errorMessage = null;
            this.$refs.text_field.focus();
        }
    }
}
</script>

<style>

</style>