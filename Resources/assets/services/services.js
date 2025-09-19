/*
 *  Copyright 2025.  Baks.dev <admin@baks.dev>
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is furnished
 *  to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
 */

$addButtonPeriod = document.getElementById('periodAddCollection');

if($addButtonPeriod)
{
    /* Блок для новой коллекции */
    let $blockCollectionCall = document.getElementById('collection-period');

    if($blockCollectionCall)
    {

        $addButtonPeriod.addEventListener('click', function()
        {

            let $addButtonPeriod = this;
            /* получаем прототип коллекции  */
            let newForm = $addButtonPeriod.dataset.prototype;
            let index = $addButtonPeriod.dataset.index * 1;

            /* Замена '__name__' в HTML-коде прототипа
             вместо этого будет число, основанное на том, сколько коллекций */
            newForm = newForm.replace(/__periods__/g, index);

            /* Вставляем новую коллекцию */
            let div = document.createElement('div');
            div.id = 'service_form_period_' + index;
            div.classList.add('mb-3');

            // div.classList.add('gap-3');
            // div.classList.add('item-collection-file');

            div.innerHTML = newForm;
            $blockCollectionCall.append(div);


            /* Удалить */
            (div.querySelector('.del-item-period'))?.addEventListener('click', deletePeriod);

            const delButton = div.querySelector('.del-item-period');
            delButton.dataset.delete='service_form_period_' + (index).toString()


            /* Увеличиваем data-index на 1 после вставки новой коллекции */
            $addButtonPeriod.dataset.index = (index + 1).toString();

            /* Плавная прокрутка к элементу */
            div.scrollIntoView({block: "center", inline: "center", behavior: "smooth"});


        });
    }
}


document.querySelectorAll('.del-item-period').forEach(function(item)
{
    item.addEventListener('click', deletePeriod);
});

function deletePeriod()
{
    document.getElementById(this.dataset.delete).remove();
}





