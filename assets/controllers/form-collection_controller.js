import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["collectionContainer"]

    static values = {
        index    : Number,
        prototype: String,
        indexStep : Number
    }

    addCollectionElement(event)
    {
        const item = document.createElement('div');
        let proto = this.prototypeValue
            .replace(/__name__/g, this.indexValue)
        item.innerHTML = proto + "<hr style=\"width: 50%; margin-left: auto; margin-right: auto; color: black;\">";
        this.collectionContainerTarget.appendChild(item);


        //Delete button
        const deleteButton = document.createElement('button');
        deleteButton.classList.add('btn', 'btn-danger', 'remove', 'btnMargin');
        deleteButton.innerHTML = 'Supprimer';
        deleteButton.addEventListener('click', () => {
            item.remove();
        });
        item.appendChild(deleteButton);

    }

    deleteCollectionElement(event) {
        console.log('coucou');
    }

    connect() {
        this.collectionContainerTarget.addEventListener("click", (event) => {
                this.indexValue = 3;
                this.deleteCollectionElement(event);

        });
    }

}