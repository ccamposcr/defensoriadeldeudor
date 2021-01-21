<ul>
    <app-client-list
        v-for="item in users"
        v-bind:item="item"
        v-bind:key="item.id"
    ></app-client-list>
</ul>