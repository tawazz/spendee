<template lang="html" id="error-404">
  <div class="wrapper">
      <div>
          <h1>Oops 404 not found</h1>
          <p>
              The page you are looking for could not be found.<br/>
              Check the address bar to ensure your URL is spelled correctly.
              If all else fails, you can visit our dashboard page at the link below.
          </p>
          <router-link :to="{ name: 'cg_main'}">Visit Dashboard Page</router-link>
      </div>
  </div>
</template>

<script>
export default {
  name:'error-404',
}
</script>

<style lang="css">
    .wrapper{
        display: flex;
        justify-content: left center;
        align-items: center;
        min-height: 100%;
        min-width:100%;
        position: absolute;
        bottom:10px;
        color: #333;
    }
    .wrapper h1{
        font-size: 4em;
    }
</style>
