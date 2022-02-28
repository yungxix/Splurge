<template>
    <div>
        <template v-for="type in types" :key="type">
            <SearchComponent :tag="tag" :search="search" @done="done()"  :type="type" @empty="trackEmpty" />
        </template>

        <div v-if="loading" class="flex flex-row justify-center py-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
        </div>

        <div v-if="allEmpty">
            <p class="text-center py-12">
                <em>
                    No result was found
                </em>
            </p>
        </div>
    </div>
</template>

<script>
import SearchComponent from './tagged-loader';


export default {
    components: {SearchComponent},
    props: {
        search: String,
        types:  {
            type: Array,
            required: true
        },
        tag: {
            type: [Number, String],
            required: true
        }
    },
    data() {
        return {
            allEmpty: false,
            emptyCount: 0,
            loading: true
        };
    },
    methods: {
        trackEmpty() {
            if (this.emptyCount === this.tags.length) {
                return;
            }
            this.emptyCount = this.emptyCount + 1;
            this.allEmpty = this.emptyCount >= this.tags.length;
        },
        done() {
            if (this.loading) {
                this.loading = false;
            }
        }
    }
}
</script>

<style>

</style>