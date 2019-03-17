<template lang="pug">
  nav(
    aria-label='Page navigation controls'
  )
    ul.pagination(v-if='showPagination')
      li.page-item(v-if='pagination.current_page > 1')
        nuxt-link.page-link(
          :to='$mergeRouteQuery({ page: pagination.current_page - 1 })'
          aria-label='Previous'
          rel='previous'
        )
          span(aria-hidden='true') «
      li.page-item(v-if='pagination.current_page - range > 2')
        span.page-link.pagination-ellipsis …
      li.page-item(v-for='page in middlePageNumbers', :class="{ 'active': page == activePage }")
        nuxt-link.page-link(
          :to='$mergeRouteQuery({ page })'
        ) {{ page }}
      li.page-item(v-if='pagination.current_page + range < pagination.last_page - 1')
        span.page-link.pagination-ellipsis …
      li.page-item(v-if='pagination.current_page < pagination.last_page')
        nuxt-link.page-link(
          :to='$mergeRouteQuery({ page: pagination.current_page + 1 })'
          aria-label='Next'
          rel='next'
        )
          span(aria-hidden='true') »
</template>

<script>
/**
 * makes a sequence starting from zero to len
 * @param  integer len
 * @return array<integer>
 */
function sequence(len) {
  return Array.apply(null, Array(len));
}

export default {
  name: 'LengthAwarePaginator',
  props:{
    pagination: {
      // current_page: 1
      // data: [,…]
      // first_page_url: "http://api.lndebate.test/questions?page=1"
      // from: 1
      // last_page: 4
      // last_page_url: "http://api.lndebate.test/questions?page=4"
      // next_page_url: "http://api.lndebate.test/questions?page=2"
      // path: "http://api.lndebate.test/questions"
      // per_page: 15
      // prev_page_url: null
      // to: 15
      // total: 55
      type: Object,
      required: true,
    },
    range: {
      type: Number,
      default () {
        return 3;
      },
    },
  },
  computed: {
    showPagination () {
      return this.pagination.total > this.pagination.per_page;
    },
    activePage () {
      return this.pagination.current_page;
    },
    middlePageNumbers () {
      const { range } = this
      const { current_page, last_page } = this.pagination
      return sequence(1 + (2 * range))
        .map((x, y) => (current_page + y) - range)
        .filter(v => v > 0 && v <= last_page)
      ;
    },
  },
}
</script>

<style lang="sass">
.pagination-ellipsis
  cursor: default
</style>
