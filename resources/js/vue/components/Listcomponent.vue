<template>
    <div>
        <h1>Post List</h1>

        <o-button @click="clickMe">Click Me</o-button>

        <o-field label="Email" variant="danger">
            <o-input type="Email" value="example@mail.com"></o-input>
        </o-field>

    </div>
</template>
<script>
    export default {
        methods:{
            clickMe(){
                alert('Funciono Oruga')
            }
        }
    }
</script>