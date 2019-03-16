<template lang="pug">
  .question-show
    QuestionCardRow(
      :item="question"
      expanded
    )
    b-nav(tabs)
      b-nav-item(
        :to='`/r/${$route.params.slug}/sides`'
      )
        fa(icon="list" fixed-width)
        | &#32;
        | Sides
      b-nav-item(
        :to='`/r/${$route.params.slug}/answers`'
      )
        fa(icon="list" fixed-width)
        | &#32;
        | Answers
      b-nav-item(
        :to='`/r/${$route.params.slug}/comments`'
      )
        fa(icon="list" fixed-width)
        | &#32;
        | Comments
    nuxt-child(:question="question")
</template>

<script>
import axios from 'axios'
import QuestionCardRow from '~/components/Question/CardRow'
import bNav from 'bootstrap-vue/es/components/nav/nav';
import bNavItem from 'bootstrap-vue/es/components/nav/nav-item';

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
