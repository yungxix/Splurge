<template>
  <div>
      <div v-if="message.text" class="p-4 flex flex-row items-center rounded shadow"
       :class="{'bg-green-200 text-green-800': message.type === 'success', 'bg-red-200 text-red-700': message.type === 'error'}">
            <svg v-if="isComplete" xmlns="http://www.w3.org/2000/svg" class="flex-initial h-12 w-12 text-green-800" viewBox="0 0 20 20" fill="currentColor">
                <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" v-if="failed" class="h-12 w-12 text-red-700" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            <p  v-text="message.text">

            </p>
            <p v-if="isComplete">
                <a href="#" @click.prevent="reset()">
                   Add another 
                </a>
            </p>
            <p v-if="Failed">
                <a href="#" @click.prevent="reset()">
                   Try again 
                </a>
            </p>
      </div>
      <div v-if="isUploading" class="flex flex-col justify-center items-center p-10">
          <p class="text-center">
              Uploading...
          </p>
          <div class="w-full relative h-2">
              <div class="abolute bg-purple-900 left-0 top-0 h-full duration-300 transition" :style="progressStyle">

              </div>
          </div>
      </div>
      <div v-if="'idle' === status">
          <form @submit.prevent="upload()" novalidate enctype="multipart/form-data" method="POST">
            <container :has-error="hasError('name')" label="Name/title of the picture">
                <input class="control w-full" type="text" v-model="formData.name" />
                <p v-if="errors.name" class="text-red-700 text-base" v-text="errors.name">

                </p>
            </container>
            <container :has-error="hasError('medium_file')" label="Picture">
                <input type="file" ref="medium_file" accept="images/*" @change="acceptFile" />
                <p v-if="errors.medium_file" class="text-red-700 text-base" v-text="errors.medium_file">

                </p>
            </container>
            <div class="flex flex-row justify-end items-center gap-x-8 p-4 content-end">
                <button type="submit" class="btn">
                    Upload
                </button>
                <slot name="buttons">

                </slot>
            </div>
          </form>
      </div>
  </div>
</template>

<script>
import axios from 'axios';
import {has} from 'lodash';
import Container from '../forms/horizontal-container';

export default {
    emits: ['statusChange', 'complete'],
    components: {Container},
    props: {
        url: {
            type: String,
            required: true
        },
        owner: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            status: 'idle',
            progress: 0,
            message: {type: 'default', text: ''},
            errors: {},
            formData: {
                name: '',
                mediaFile: null
            }
        };
    },
    methods : {
        reset() {
            this.progress = 0;
            this.message = {type: 'default', text: ''};
            this.status = 'idle';
            this.formData = {name: '', mediaFile: null};
        },
        hasError(field) {
            return has(this.errors, field);
        },
        upload() {
            if (!this.formData.name) {
                this.errors = {
                    name: 'Give the picture a caption'
                };
                return;
            }
            if (!this.formData.mediaFile) {
                this.errors = {
                    medium_file: 'You have not selected a file'
                };
                return;
            }

            this.uploadImpl();

        },
        notifyStatusChange() {
            this.$emit('statusChange', {
                status: this.status
            });
        },
        async uploadImpl() {
            this.status = 'uploading';
            this.notifyStatusChange();
            this.message = {type: 'default', text: ''};
            
            const form = new FormData();
            form.append('name', this.formData.name);
            form.append('medium_file', this.formData.mediaFile);
            
            Object.keys(this.owner).forEach((key) => {
                form.append(`owner_${key}`, this.owner[key]);
            });
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

            form.append("_token", csrfToken);

            try {
                const resp = await axios.post(this.url, form, {
                    onUploadProgress: (e) => {
                        this.$nextTick(() => {
                            this.progress = (e.loaded / e.total) * 100;
                        });
                    },
                    withCredentials: true
                });

                this.status = 'complete';
                this.notifyStatusChange();
                this.message = {
                    type: 'success',
                    text: `New picture for '${resp.data.data.name}' has been uploaded`
                };
                this.$emit("complete", resp.data.data);
            } catch(ex) {
                this.status = 'error';
                this.notifyStatusChange();
                this.message = {
                    type: 'error',
                    text: ex.message
                };
            }



        },
        acceptFile(event) {
            if (event.target.files.length === 0) {
                return;
            }
            const file = event.target.files[0];
            if (!(/image\/.+/i).test(file.type)) {
                this.errors = {
                    ...this.errors,
                    medium_file: 'Invalid file type'
                };
                return;
            }
            this.formData.mediaFile = file;
        }
    },
    computed: {
        isUploading() {
            return 'uploading' === this.status;
        },
        isComplete() {
            return 'complete' === this.status;
        },
        failed() {
            return 'error' === this.status;
        },
        isPending() {
            return 'idle' === this.status; 
        },
        progressStyle() {
            return {
                width: `${this.progress}%`
            };
        }
    }
}
</script>

<style>

</style>