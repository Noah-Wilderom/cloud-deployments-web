<script setup>
import { useActions, useTable } from 'inertiaui/table'

const props = defineProps('resource')

const {
    addFilter,
    hasBulkActions,
    hasFilters,
    isNavigating,
    isSortedByColumn,
    removeFilter,
    setPerPage,
    setSort,
    state,
    toggleColumn,
    visitTableUrl,
} = useTable(props.resource)

const {
    allItemsAreSelected,
    isPerformingAction,
    performAction,
    selectedItems,
    toggleItem,
} = useActions()
</script>
