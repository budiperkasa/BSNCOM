					{if $checkboxes|@count != 0}
					<div class="content_field_output">
						<strong>{if $field->frontend_name}{$field->frontend_name}{else}{$field->name}{/if}</strong>:
						{foreach from=$checkboxes item=checkbox}
							{if $checkbox.id == 1}
								<img src="{$public_path}/images/pictograms/24hr_frontdesk.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 2}
								<img src="{$public_path}/images/pictograms/Airconditioning.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 3}
								<img src="{$public_path}/images/pictograms/Airport_Shuttle.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 4}
								<img src="{$public_path}/images/pictograms/All_Public.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 5}
								<img src="{$public_path}/images/pictograms/Allergy_Free_Room_Available.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 6}
								<img src="{$public_path}/images/pictograms/ATM_Cash_Machine.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 7}
								<img src="{$public_path}/images/pictograms/Babysitting.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 8}
								<img src="{$public_path}/images/pictograms/Babysitting_Child_Services.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 9}
								<img src="{$public_path}/images/pictograms/Banquet_Facilities.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 10}
								<img src="{$public_path}/images/pictograms/Bar.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 11}
								<img src="{$public_path}/images/pictograms/Barber_Beauty_Shop.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 12}
								<img src="{$public_path}/images/pictograms/BBQ_Facilities.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 13}
								<img src="{$public_path}/images/pictograms/Bicycle_Rental.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 14}
								<img src="{$public_path}/images/pictograms/Billiards.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 15}
								<img src="{$public_path}/images/pictograms/Breakfast_in_the_Room.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 16}
								<img src="{$public_path}/images/pictograms/Bridal_Suite.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 17}
								<img src="{$public_path}/images/pictograms/Business_Centre.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 18}
								<img src="{$public_path}/images/pictograms/Cafe.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 19}
								<img src="{$public_path}/images/pictograms/Canoeing.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 20}
								<img src="{$public_path}/images/pictograms/Car_Hire.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 21}
								<img src="{$public_path}/images/pictograms/Casino.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 22}
								<img src="{$public_path}/images/pictograms/Chapel_Shrine.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 23}
								<img src="{$public_path}/images/pictograms/Children_Playground.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 24}
								<img src="{$public_path}/images/pictograms/Children_Swimming_Pool.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 25}
								<img src="{$public_path}/images/pictograms/Coffeeshop.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 26}
								<img src="{$public_path}/images/pictograms/Concierge_Service.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 27}
								<img src="{$public_path}/images/pictograms/Currency_Exchange.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 28}
								<img src="{$public_path}/images/pictograms/Cycling.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 29}
								<img src="{$public_path}/images/pictograms/Darts.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 30}
								<img src="{$public_path}/images/pictograms/Design_Hotel.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 31}
								<img src="{$public_path}/images/pictograms/Designated_Smoking_Area.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 32}
								<img src="{$public_path}/images/pictograms/Disabled_Guests.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 33}
								<img src="{$public_path}/images/pictograms/Diving.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 34}
								<img src="{$public_path}/images/pictograms/Dry_Cleaning.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 35}
								<img src="{$public_path}/images/pictograms/Elevator.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 36}
								<img src="{$public_path}/images/pictograms/Express_Check_In.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 37}
								<img src="{$public_path}/images/pictograms/Family_Rooms.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 38}
								<img src="{$public_path}/images/pictograms/Fax_Photocopying.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 39}
								<img src="{$public_path}/images/pictograms/Fishing.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 40}
								<img src="{$public_path}/images/pictograms/Fitness_Centre.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 41}
								<img src="{$public_path}/images/pictograms/Games_Room.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 42}
								<img src="{$public_path}/images/pictograms/Garden.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 43}
								<img src="{$public_path}/images/pictograms/Gay_Friendly.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 44}
								<img src="{$public_path}/images/pictograms/Golf_Course.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 45}
								<img src="{$public_path}/images/pictograms/Hammam.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 46}
								<img src="{$public_path}/images/pictograms/Heating.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 47}
								<img src="{$public_path}/images/pictograms/Hiking.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 48}
								<img src="{$public_path}/images/pictograms/Indoor_Swimming_Pool.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 49}
								<img src="{$public_path}/images/pictograms/Ironing_Service.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 50}
								<img src="{$public_path}/images/pictograms/Jacuzzi.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 51}
								<img src="{$public_path}/images/pictograms/Karaoke.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 52}
								<img src="{$public_path}/images/pictograms/Laundry.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 53}
								<img src="{$public_path}/images/pictograms/Library.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 54}
								<img src="{$public_path}/images/pictograms/Luggage_Storage.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 55}
								<img src="{$public_path}/images/pictograms/Massage.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 56}
								<img src="{$public_path}/images/pictograms/Mini_Golf.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 57}
								<img src="{$public_path}/images/pictograms/Newspapers.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 58}
								<img src="{$public_path}/images/pictograms/Nightclub.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 59}
								<img src="{$public_path}/images/pictograms/Non_Smoking_Rooms.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 60}
								<img src="{$public_path}/images/pictograms/Outdoor_Swimming_Pool.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 61}
								<img src="{$public_path}/images/pictograms/Packed_Lunches.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 62}
								<img src="{$public_path}/images/pictograms/Parking.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 63}
								<img src="{$public_path}/images/pictograms/Piano_Bar.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 64}
								<img src="{$public_path}/images/pictograms/Private_Check_in.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 65}
								<img src="{$public_path}/images/pictograms/Private_Garden.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 66}
								<img src="{$public_path}/images/pictograms/Private_parking.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 67}
								<img src="{$public_path}/images/pictograms/Restaurant.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 68}
								<img src="{$public_path}/images/pictograms/Restaurant_buffet.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 69}
								<img src="{$public_path}/images/pictograms/Restaurant_la_carte.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 70}
								<img src="{$public_path}/images/pictograms/Room_Service.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 71}
								<img src="{$public_path}/images/pictograms/Safety_Deposit_Box.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 72}
								<img src="{$public_path}/images/pictograms/Sauna.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 73}
								<img src="{$public_path}/images/pictograms/Shoe_Shine.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 74}
								<img src="{$public_path}/images/pictograms/Shops_in_Hotel.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 75}
								<img src="{$public_path}/images/pictograms/Shuttle_Service_free.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 76}
								<img src="{$public_path}/images/pictograms/Shuttle_Service_surcharge.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 77}
								<img src="{$public_path}/images/pictograms/Ski_School.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 78}
								<img src="{$public_path}/images/pictograms/Ski_Storage.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 79}
								<img src="{$public_path}/images/pictograms/Skiing.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 80}
								<img src="{$public_path}/images/pictograms/Snack_Bar.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 81}
								<img src="{$public_path}/images/pictograms/Snorkelling.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 82}
								<img src="{$public_path}/images/pictograms/Solarium.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 83}
								<img src="{$public_path}/images/pictograms/Soundproofed_Rooms.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 84}
								<img src="{$public_path}/images/pictograms/Souvenirs_Gift_Shop.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 85}
								<img src="{$public_path}/images/pictograms/Spa_Wellness_Centre.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 86}
								<img src="{$public_path}/images/pictograms/Specia_Diet_Menus.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 87}
								<img src="{$public_path}/images/pictograms/Squash.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 88}
								<img src="{$public_path}/images/pictograms/Steam_Room.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 89}
								<img src="{$public_path}/images/pictograms/Sun_Terrace.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 90}
								<img src="{$public_path}/images/pictograms/Table_Tennis.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 91}
								<img src="{$public_path}/images/pictograms/Tennis_Court.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 92}
								<img src="{$public_path}/images/pictograms/Terrace.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 93}
								<img src="{$public_path}/images/pictograms/Ticket_Service.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 94}
								<img src="{$public_path}/images/pictograms/Tour_Desk.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 95}
								<img src="{$public_path}/images/pictograms/Trouser_Press.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 96}
								<img src="{$public_path}/images/pictograms/Turkish_Steam_Bath.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 97}
								<img src="{$public_path}/images/pictograms/Vending_Machine_drinks.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 98}
								<img src="{$public_path}/images/pictograms/Vending_Machine_snacks.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 99}
								<img src="{$public_path}/images/pictograms/VIP_Room_Facilities.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 100}
								<img src="{$public_path}/images/pictograms/Wifi_free.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 101}
								<img src="{$public_path}/images/pictograms/Windsurfing.gif" title="{$checkbox.option_name}" />
							{/if}
							{if $checkbox.id == 104}
								<img src="{$public_path}/images/pictograms/Wired_internet.gif" title="{$checkbox.option_name}" />
							{/if}
						{/foreach}
					</div>
					{/if}