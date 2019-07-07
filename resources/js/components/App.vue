<template>
  <div>
      <router-view />

      <modal v-if="showModal">
          <h5 slot="header">
              {{ modalTitle }}
          </h5>
          <button slot="header" type="button" class="close" @click="showModal = false">
              <span >&times;</span>
          </button>
          <p slot="body">
              {{ modalText }}
          </p>
          <button slot="footer" class="btn btn-primary" @click="showModal = false">Ok</button>
      </modal>
 </div>
</template>
<script>
    export default {
        /*
         * If the user is red{
   ed back from a callback, the URL will contain a query with code,
         * if this is the case we'll make sure they get sent to the next screen as they've logged in.
         */
        created() {
            if('undefined' != typeof(this.$route.query.code)) {
                this.$router.push('/'); // TODO set correct route
            }
        },
        mounted() {
            this.$root.$on("show-modal", (title, message) => {
                this.openModal(title, message);
            });
        },
        data() {
            return {
              showModal: false,
              modalTitle: 'Test Title',
              modalText: 'You shuold not see this',
            };
        },
        methods: {
            openModal(title, text) {
                this.modalTitle = title;
                this.modalText = text;
                this.showModal = true;
            },
            closeModal() {
                this.showModal = false;
            }
        }
    };
</script>
