// resources/assets/js/components/TaskList.vue

<template>
  <div class="row">
    <div class="col-sm-4 mb-1" v-for="task in tasks" :key="task.id">
      <div class="card">
        <div class="card-body" :class="getclass(task)">
          <div class="row">
            <strong>
              {{ task.title }}
            </strong>
            <p>
              {{ task.message }}
            </p>
            <input
              type="checkbox"
              v-model="task.completed"
              class="form-control"
              @change="updateStatus(task)"
            />
          </div>
        </div>

        <small> {{ task.user.name }}</small>
        <div class="card card-default">
          <div class="card-header">Comments</div>
          <div class="card-body">
            <comment-list :comments="task.comments"></comment-list>
          </div>
        </div>
        <comment @commenttask="commentTask" :task="task" :user="user"></comment>
      </div>
    </div>
  </div>
</template>

<script>
import Comment from "./Comment.vue";
import CommentList from "./CommentList.vue";
export default {
  components: {
    Comment,
    CommentList,
  },
  props: ["tasks", "user"],
  methods: {
    getclass(task) {
      var color = "";
      if (task.completed == 1) {
        return "bg-green";
      } else {
        if (task.user_id == this.user.id) {
          return "bg-blue";
        } else {
          return "bg-light";
        }
      }
    },
    updateStatus(task) {
      this.$emit("taskupdate", task);
    },
    commentTask(data) {
      data.task.comments.push(data.comment);
    },
  },
};
</script>
<style scoped>
.bg-green {
  background: green;
  color: white;
}
.bg-blue {
  background: lightblue;
  color: white;
}
</style>
