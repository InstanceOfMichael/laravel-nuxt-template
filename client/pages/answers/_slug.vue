<template lang="pug">
  .answer-show
    AnswerCardRow(
      :item="answer"
      expanded
    )
    b-nav(tabs)
      b-nav-item(
        :to="{ name: 'answers-slug', params: { slug: $slug(answer) } }"
      )
        fa(icon="list" fixed-width)
        | &#32;
        | Index
      b-nav-item(
        :to="{ name: 'answers-slug-comments', params: { slug: $slug(answer) } }"
      )
        fa(icon="list" fixed-width)
        | &#32;
        | {{ answer.comments_count }} Comments
      b-nav-item(
        :to="{ name: 'answers-slug-topics', params: { slug: $slug(answer) } }"
      )
        fa(icon="list" fixed-width)
        | &#32;
        | {{ answer.topics_count }} Topics
    nuxt-child(:answer="answer")
</template>

<script>
import AnswerCardRow from '~/components/Answer/CardRow'
import bNav from 'bootstrap-vue/es/components/nav/nav'
import bNavItem from 'bootstrap-vue/es/components/nav/nav-item'

export default {
  components: {
    AnswerCardRow,
    bNav,
    bNavItem,
  },
  async asyncData ({ api, route, deslug }) {
    return {
      answer: (await api.get(`/answers/${deslug(route.params.slug)}`)).data,
    }
  },
}
</script>

<style lang="sass">

</style>
