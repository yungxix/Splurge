<template>
  <div v-if="loaded" class="relative">
      <div ref="search_content" v-html="content"></div>
      <div v-if="navigating" class="absolute left-0 top-0 w-full z-20 flex flex-row justify-center items-center py-8 bg-transparent">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
      </div>
  </div>
</template>

<script>
import axios from "axios";
import {noop} from 'lodash';
export default {
    emits: ['failed', 'empty', 'done'],
    props: {
        type: {
            type: String,
            required: true
        },
        tag: {
            type: [Number, String],
            required: true
        },
        query: String,
        noContent: Function
    },
    data() {
        return {
            content: '',
            loaded: false,
            navigating: false
        };
    },
    mounted() {
        axios.get('/search/tagged', {
            params: {
                tag: this.tag,
                q: this.query,
                type: this.type
            },
            headers: {
                'Accept': 'text/html'
            }
        }).then((resp) => {
            this.content = resp.data || '';
            if (this.content.length < 100) {
                this.$emit('empty');
                (this.noContent || noop)();
                this.$emit('done', {empty: true});

            } else {
                this.loaded = true;
                this.$emit('done', {empty: false});
                this.$nextTick(() => {
                  this.trapPaging();
              });
            }
        }, (err) => {
            (this.noContent || noop)();
            console.error('Failed to load taggeable %s', this.type);
            console.error(err);
            this.$emit('failed');
            this.$emit('done', {empty: true});
        });
    },
    methods : {
        trapPaging() {
            const nav = this.$refs.search_content.querySelector('nav[role="navigation"]');
            if (nav) {
                nav.querySelectorAll('a[href]').forEach((anchor) => {
                    anchor.onclick = (e) => {
                        e.preventDefault();
                        this.loadUrl(anchor.getAttribute('href'));
                        return false;

                    };
                });
            }
        },
        async loadUrl(url) {
            this.navigating = true;
            try {
              const resp =  await  axios.get(url, {
                  headers: {
                      'Accept': 'text/html'
                  }
              });
              this.content = resp.data || `<p class="text-center">
                <em>No content</em>
              </p>`;
              this.$nextTick(() => {
                  this.trapPaging();
              });
            } catch (error) {
                
            } finally {
                this.navigating = false;
            }
        }
    }
}
</script>

<style>

</style>