<template lang="pug">
  .question-show
    QuestionCardRow(
      :item="question"
      expanded
    )
    b-nav(tabs)
      b-nav-item(
        :to="{ name: 'questions-slug', params: { slug: $slug(question) } }"
      )
        fa(icon="list" fixed-width)
        | &#32;
        | Index
      b-nav-item(
        :to="{ name: 'questions-slug-answers', params: { slug: $slug(question) } }"
      )
        fa(icon="list" fixed-width)
        | &#32;
        | {{ question.answers_count }} Answers
      b-nav-item(
        :to="{ name: 'questions-slug-comments', params: { slug: $slug(question) } }"
      )
        fa(icon="list" fixed-width)
        | &#32;
        | {{ question.comments_count }} Comments
      b-nav-item(
        v-if="question.sides_type !== 0"
        :to="{ name: 'questions-slug-sides', params: { slug: $slug(question) } }"
      )
        fa(icon="list" fixed-width)
        | &#32;
        | {{ question.sides_count }} Sides
      b-nav-item(
        :to="{ name: 'answers-slug-topics', params: { slug: $slug(questions) } }"
      )
        fa(icon="list" fixed-width)
        | &#32;
        | {{ question.topics_count }} Topics
    nuxt-child(:question="question")
</template>

<script>
import QuestionCardRow from '~/components/Question/CardRow'
import bNav from 'bootstrap-vue/es/components/nav/nav'
import bNavItem from 'bootstrap-vue/es/components/nav/nav-item'

export default {
  components: {
    QuestionCardRow,
    bNav,
    bNavItem,
  },
  async asyncData ({ api, route, deslug }) {
    return {
      question: (await api.get(`/questions/${deslug(route.params.slug)}`)).data,
    }
  },
}
</script>

<style lang="sass">

</style>
