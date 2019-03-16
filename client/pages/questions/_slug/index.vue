<template lang="pug">
  .question-show
    | @TODO
    pre: tt {{ {question} }}
</template>

<script>
import axios from 'axios'

export default {
  async asyncData ({ api, route, deslug }) {
    return {
      user: (await api.get(`/questions/${deslug(route.params.slug)}`)).data,
    }
  },
}
</script>
