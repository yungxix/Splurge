<template>
  <div :style="rootStyle" class="relative md:w-52">
      <template v-for="item in decoratedMessages" :key="item.id">
          <div :class="getItemClass(item)" class="ease-in clear-both px-4 py-2 w-full rounded mb-2 duration-300 transition-opacity bg-opacity-70">
              <a @click="close(item.id)" class="float-right cursor-pointer hover:font-boldp-2">&times;</a>
              <p v-text="item.message"></p>
          </div>
      </template>
  </div>
</template>

<script>
import {uniqueId} from 'lodash';
export default {
    props: {
        messages: {
            type: Array,
            required: true
        },
        delay: {
            type: Number,
            default: 3000
        }
    },
    data() {
        return {
            decoratedMessages: this.messages.map((m) => {
                return {
                    ...m,
                    closing: false,
                    id: uniqueId("flash_")
                };
            }),
            scrollTop: 0
        };
    },
    computed: {
        rootStyle() {
            return {
                paddingTop: `${this.scrollTop}px`
            };
        }
    },
    mounted() {
        setTimeout(() => {
            this.closeNextImpl();
        }, this.delay);
        this.scrollTop = window.scrollY + 10;
    },
    methods : {
        getItemClass(item) {
            switch (item.type) {
                case 'success':
                    return 'text-white bg-green-800 ' + this.getClosingClass(item);
                case 'warning':
                case 'warn':
                    return 'text-black bg-yellow-500 ' + this.getClosingClass(item);
                case 'danger':
                case 'error':
                    return 'text-white bg-red-700 '  + this.getClosingClass(item);
                default:
                    return 'text-white bg-gray-700 ' + this.getClosingClass(item);
            }
        },
        getClosingClass(item) {
            return item.closing ? 'opacity-10' : 'opacity-100';
        },
        closeNext() {
            this.closeNextImpl();
        },
        closeNextImpl() {
            if (this.decoratedMessages.some(x => x.important !== true)) {
                this.decoratedMessages = this.decoratedMessages.map((item) => {
                    if (!item.closing && item.important !== true) {
                        this.scheduleRemove(item.id);
                        return {
                            ...item,
                            closing: true
                        };
                    }
                    return item;
                });


            }
        },
        close(id) {
            this.decoratedMessages = this.decoratedMessages.map((item) => {
                if (!item.closing && item.id === id) {
                    this.scheduleRemove(item.id);
                    return {
                        ...item,
                        closing: true
                    };
                }
                return item;
            });
        },
        scheduleRemove(id) {
            setTimeout(() => {
                this.decoratedMessages = this.decoratedMessages.filter(x => x.id !== id);
                this.closeNextImpl();
            }, 1000);
        }
    }
}
</script>