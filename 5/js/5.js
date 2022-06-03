const vm = new Vue({
    el: '#app',
    components: {
        VueBootstrapTable: VueBootstrapTable
    },
    data: {
        showSelect: false,
        multiColumnSortable: true,
        visible: false,
        participants: '',
        columns: [
            {
                title:"id",
                visible: true,
            },
            {
                title:"Имя",
                name: "name",
                visible: true,
            },
            {
                title:"Очки",
                name: "score",
                visible: true,
            },
        ],
        values: [
        ]
    },

    methods: {
        send: function () {
            const cyrillicPattern = /[^а-яёА-ЯЁ, ]/
            if (cyrillicPattern.test(this.participants)) {
                alert('Только кириллические символы')
            } else if (this.participants === '') {
                alert('Поле не заполнено')
            } else {
                const array = this.participants.split(/[,]+/);
                let inputStatus = true
                array.forEach(element => {
                    if (element === '')  {
                        alert('Некорректный ввод')
                        inputStatus = false
                    }
                })
                console.log(inputStatus)
                if (inputStatus) array.forEach(element => this.addItem(element))
            }
        },
        addItem: function(element) {
            const item = {
                "id": this.values.length + 1,
                "name": element,
                "score": Math.floor(Math.random() * 10000),
            };
            this.visible = true
            this.participants = ''
            this.values.push(item);
        },
    },
});



