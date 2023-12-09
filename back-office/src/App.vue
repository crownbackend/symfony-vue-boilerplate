<script setup lang="ts">
import { onMounted } from "vue";
import { Tooltip, initTE } from "tw-elements";

onMounted(() => {
  initTE({ Tooltip });
});
</script>

<template>
  <router-view/>
</template>

<style scoped>

</style>
