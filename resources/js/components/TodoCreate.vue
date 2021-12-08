// resources/assets/js/components/TodoCreate.vue

<template>
  <div class="row">
    <div class="form-group col-sm-5">
      <input
        id="btn-input"
        type="text"
        name="message"
        class="form-control input-sm"
        placeholder="Type your subject here..."
        v-model="newTask.title"
        @keyup.enter="createTask"
      />
    </div>
    <div class="form-group col-sm-5">
      <textarea
        name="message"
        class="form-control"
        placeholder="Type your message here..."
        v-model="newTask.message"
        @keyup.enter="createTask"
        rows="3"
      ></textarea>
    </div>
    <div class="col-sm-2">
      <button class="btn btn-primary btn-sm" id="btn-chat" @click="createTask">
        Send
      </button>
    </div>
  </div>
</template>

<script>
export default {
  props: ["user"],

  data() {
    return {
      newTask: {
        title: "",
        message: "",
        user: this.user,
      },
    };
  },

  methods: {
    createTask() {
      this.$emit("taskstore", this.newTask);
      this.newTask = {
        title: "",
        message: "",
        user: this.user,
      };
    },
  },
};
</script>
