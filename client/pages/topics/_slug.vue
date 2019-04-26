<template lang="pug">
  .topic-show
    TopicCardRow(
      :item="topic"
      expanded
    )
    b-nav(tabs)
      b-nav-item(
        :to="{ name: 'topics-slug', params: { slug: $slug(topic) } }"
      )
        fa(icon="list" fixed-width)
        | &#32;
        | Index
      b-nav-item(
        :to="{ name: 'topics-slug-answers', params: { slug: $slug(topic) } }"
      )
        fa(icon="list" fixed-width)
        | &#32;
        | {{ topic.answers_count }} Answers
      b-nav-item(
        :to="{ name: 'topics-slug-comments', params: { slug: $slug(topic) } }"
      )
        fa(icon="list" fixed-width)
        | &#32;
        | {{ topic.comments_count }} Comments
      b-nav-item(
        v-if="topic.sides_type !== 0"
        :to="{ name: 'topics-slug-sides', params: { slug: $slug(topic) } }"
      )
        fa(icon="list" fixed-width)
        | &#32;
        | {{ topic.sides_count }} Sides
      b-nav-item(
        :to="{ name: 'topics-slug-topics', params: { slug: $slug(topic) } }"
      )
        fa(icon="list" fixed-width)
        | &#32;
        | {{ topic.topics_count }} Topics
    nuxt-child(:topic="topic")
</template>

<script>
import TopicCardRow from '~/components/Topic/CardRow'
import bNav from 'bootstrap-vue/es/components/nav/nav'
import bNavItem from 'bootstrap-vue/es/components/nav/nav-item'

export default {
  components: {
    TopicCardRow,
    bNav,
    bNavItem,
  },
  async asyncData ({ api, route, deslug }) {
    return {
      topic: (await api.get(`/topics/${deslug(route.params.slug)}`)).data,
    }
  },
}
</script>

<style lang="sass">

</style>
