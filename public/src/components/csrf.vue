<template lang="html">
  <div>
    <input type="hidden" :name="csrf.keys.name" :value="csrf.name">
    <input type="hidden" :name="csrf.keys.value" :value="csrf.value">
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      csrf: Window.csrf
    }
  }
}
</script>
