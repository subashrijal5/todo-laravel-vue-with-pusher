// resources/assets/js/components/CommentList.vue

<template>
  <ul class="left clearfix">
    <li class="" v-for="comment in comments" :key="comment.id">
      <strong>
        {{ comment.commenter.name }}
      </strong>
      <br />
      <p>
        {{ comment.comment }}
      </p>
    </li>
  </ul>
</template>

<script>
export default {
  props: ["comments"],
  methods: {},
};
</script>

