<template>
  <p v-for="(line, index) in lines" :key="index" :class="paragraphClass" v-text="line">

  </p>
</template>

<script>
export default {
    props: {
        text: String,
        paragraphClass: String
    },
    data() {
        return {
            lines: []
        };
    },
    mounted() {
        if (this.text) {
            this.lines = this.text.split(/\n/g);
        }
    }
}
</script>

<style>

</style>