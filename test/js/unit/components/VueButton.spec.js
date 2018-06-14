import { shallowMount } from '@vue/test-utils';
import VueButton from '../../../../src/Assets/js/src/components/VueButton.vue';

describe('VueButton.vue', () => {

  it('should emit click event when clicked', () => {
    const wrapper = shallowMount(VueButton);
    wrapper.find('button').trigger('click');
    expect(wrapper.emitted('click')).toBeTruthy();
  });

});
