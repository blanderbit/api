<template>
    <div>ddd
        <router-view></router-view>
    </div>
</template>

<script>
  export default {
    name: "app",
    created(){
      this.$router.push({ name : 'catalogs'});
    }
  }
</script>

<style scoped>

</style>
