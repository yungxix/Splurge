<template>
    <div class="relative" ref="root">
        <slot name="trigger"></slot>
         <transition  enter-active-class="transition ease-out duration-200"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95">
            <div @click="$emit('close')" v-if="show" :style="contentStyle" :class="contentClass">
                <slot>

                </slot>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    props: {
        show: {
            type: Boolean,
            default: false
        },
        width: {
            type: String,
            default: '58px'
        },
        color: {
            type: String,
            default: 'white'
        },
        align: {
            type: String,
            default: 'right'
        }
    },
    emits: ["close"],
    created() {
        this.handleClose = (event) => {
            if (!this.$refs.root.contains(event.target)) {
                this.$emit('close');
            }
        };
        document.addEventListener('click', this.handleClose);
    },
    beforeUnmount() {
        document.removeEventListener('click', this.handleClose);
    },
    computed : {
        contentStyle() {
            return {
                width: this.width
            };
        },
        contentClass() {
            const positionClass = 'left' === this.align ? 'top-5 left-0' : 'top-5 left-auto right-0';
            return `bg-${this.color || 'white'} absolute ${positionClass} z-50 mt-2 rounded-md shadow-md`;
        }
    }
}
</script>

<style>

</style>