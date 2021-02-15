<template>
    <ais-instant-search :search-client="searchClient" index-name="threads_index">
        <ais-search-box/>
        <ais-state-results :search-client="searchClient" index-name="threads_index">
            <ais-search-box/>
            <template slot-scope="{ query }">
                <ais-hits v-if="query.length > 0" :escape-HTML="true">
                    <div slot="item" slot-scope="{ item }">
                        <a :href="item.path">
                            <h2>
                                <ais-highlight
                                    attribute="title"
                                    :hit="item"
                                    highlightedTagName="span"
                                />
                            </h2>
                            <p>
                                <ais-highlight
                                    attribute="body"
                                    :hit="item"
                                    highlightedTagName="span"
                                />
                            </p>
                        </a>
                    </div>
                </ais-hits>
                <div v-else>
                    <h4 class="mt-3">No results</h4>
                </div>
            </template>

        </ais-state-results>
    </ais-instant-search>
</template>

<script>
    import algoliasearch from 'algoliasearch/lite';

    export default {
        props: ['publicKey', 'appId'],
        data() {
            return {
                searchClient: algoliasearch(this.appId, this.publicKey),
            }
        }
    };
</script>

<style>
    body {
        font-family: sans-serif;
        padding: 1em;
    }

    .ais-Highlight-highlighted {
        background-color: yellow;
    }

    .ais-Hits-list {
        list-style-type: none;
        padding: 0;
        margin-top: 20px;
    }
    .ais-Hits-item {
       width: 100%;
    }
</style>
