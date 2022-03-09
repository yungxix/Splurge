<template>
  <a class="link" href="#" @click.prevent="toggle($event)">
      {{text}}
  </a>
  
  <TransitionRoot :show="opened" as="template">
    <Dialog @close="close">
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
              class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle"
            >
              <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="">
                  
                  <div class="mt-3">
                    <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                      {{title}}
                    </DialogTitle>
                    <div class="mt-2">
                      <UploadForm @complete="onComplete()" v-bind="uploadOptions">
                        <template v-slot:buttons>
                            <button class="btn" :disabled="busy" type="button" @click="close()">
                                Close
                            </button>
                        </template>
                      </UploadForm>
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
import {ref} from 'vue';
import UploadForm from './medium-uploader.vue';

 import {
    Dialog,
    DialogOverlay,
    DialogTitle,
    DialogDescription,
    TransitionRoot,
    TransitionChild
  } from "@headlessui/vue";

export default {
    components: {UploadForm, TransitionChild, TransitionRoot, DialogDescription, Dialog, DialogOverlay, DialogTitle},
    props: {
        title: String,
        uploadOptions: {
            type: Object,
            required: true
        },
        message: String,
        text: {
            type: String,
            default: 'Add a picture'
        },
        reloadOnComplete: Boolean
    },
    data() {
        return {
            opened: false,
            currentY: 0,
            busy: false
        };
    },
    methods: {
        toggle(event) {
            this.opened = !this.opened;
        },
        onStatusChange(data) {
            this.bsy = data.status === 'uploading';
        },
        close() {
            this.opened = false;
        },
        onComplete() {
          if (this.reloadOnComplete) {
            window.location.reload();
          }
        }
    }
}
</script>