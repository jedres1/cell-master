<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">
                        I'm an example component.gfddfgdgf
                        <input type="text" v-model.number="uno" />
                        <input type="text" v-model.number="dos" />
                        <select v-model="operacion">
                            <option value="sumar">Sumar</option>
                            <option value="restar">Restar</option>    
                        </select>

                        {{ message + ' ' + total }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data: function()  {
                return {
                    texto: 'hola mundo',
                    uno: 5,
                    operacion: 'sumar',
                    dos: 4,
                    message: 'El resultado es: '
                }
        },
        computed: {
            total() {
                if(this.operacion === 'sumar') {
                return Number(this.uno + this.dos)
                }
                
                return Number(this.uno - this.dos)
            }
        }
    }
</script>
