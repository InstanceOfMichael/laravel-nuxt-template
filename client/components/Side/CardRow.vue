<template lang="pug">
.card.card-side
  .card-body
    h5.card-title {{ item.name }}
    //- h6.card-title {{ item.title }}
    .card-text {{ item.text }}
    nuxt-link(
      :to="{ name: 'sides-slug', params: { slug: $slug(item) } }"
    )
      | created&#32;
      TimeAgo(:value="item.created_at")
    | &#32;
    | &#32;|&#32;
    nuxt-link(
      :to="{ name: 'questions', query: { side: $slug(item) } }"
    ) Questions
    | &#32;|&#32;
    nuxt-link(
      :to="{ name: 'claims', query: { side: $slug(item) } }"
    ) Claims
    | &#32;|&#32;
    nuxt-link(
      :to="{ name: 'topics', query: { side: $slug(item) } }"
    ) Topics
  //- .card-body.small: tt: pre {{ {item} }}
</template>

<script>
export default {
  name: 'SideCardRow',
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
.card.card-side
</style>
