<template lang="pug">
.card.card-topic
  .card-body
    h5.card-title {{ item.name }}
    //- h6.card-title {{ item.title }}
    .card-text {{ item.text }}
    nuxt-link(
      :to="{ name: 'topics-slug', params: { slug: $slug(item) } }"
    )
      | created&#32;
      TimeAgo(:value="item.created_at")
    | &#32;
    | &#32;|&#32;
    nuxt-link(
      :to="{ name: 'questions', query: { topic: $slug(item) } }"
    ) Questions
    | &#32;|&#32;
    nuxt-link(
      :to="{ name: 'claims', query: { topic: $slug(item) } }"
    ) Claims
    | &#32;|&#32;
    nuxt-link(
      :to="{ name: 'sides', query: { topic: $slug(item) } }"
    ) Sides
  //- .card-body.small: tt: pre {{ {item} }}
</template>

<script>
export default {
  name: 'TopicCardRow',
  props: {
    item: {
      type: Object,
      required: true,
    },
    expanded: {
      type: Boolean,
      default: false,
    },
  },
}
</script>

<style lang="sass">
.card.card-topic
</style>
