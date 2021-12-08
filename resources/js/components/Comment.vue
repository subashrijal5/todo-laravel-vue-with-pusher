// resources/assets/js/components/ChatForm.vue

<template>
  <div class="row">
    <div class="form-group">
      <input
        id="btn-input"
        type="text"
        name="comment"
        class="form-control input-sm"
        placeholder="Type your comment here and press enter ..."
        v-model="newComment.comment"
        @keyup.enter="doComment"
      />
    </div>
  </div>
</template>

<script>
export default {
  props: ["user", "task", "comments"],

  data() {
    return {
      newComment: {
        comment: "",
        commenter: this.user,
      },
    };
  },

  methods: {
    doComment() {
      this.$emit("commenttask", {
        task: this.task,
        comment: this.newComment,
      });
      axios
        .post("/comment/", { comment: this.newComment, taskId: this.task.id })
        .then((response) => {
          console.log(response.data);
        });

      this.newComment = {
        comment: "",
        commenter: this.user,
      };
    },
  },
};
</script>
