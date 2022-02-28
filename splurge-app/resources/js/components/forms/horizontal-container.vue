<template>
  <div class="form-group" :class="{'error': hasError}">
      <div class="label-wrapper">
          <slot name="label">
              <label class="control-label" v-text="label"></label>
          </slot>
      </div>
      <div class="control-wrapper"><slot></slot> </div>
  </div>
</template>

<script>
export default {
    props : {
        label: String,
        hasError: Boolean
    },
}
</script>