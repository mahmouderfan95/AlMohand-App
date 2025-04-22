<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE PROCEDURE update_product_serials(IN orderId BIGINT, IN productId BIGINT, IN quantity INT)
            BEGIN
                DECLARE available_count INT;
                DECLARE done INT DEFAULT 0;
                DECLARE serialId BIGINT;

                -- Declare a cursor to iterate through product_serials IDs to update
                DECLARE serialCursor CURSOR FOR
                SELECT id
                FROM product_serials
                WHERE product_id = productId
                  AND expiring >= NOW()
                  AND status = "free"
                LIMIT quantity;

                -- Declare a NOT FOUND handler for the cursor
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

                -- Check if there are enough free product_serials
                SELECT COUNT(*) INTO available_count
                FROM product_serials
                WHERE product_id = productId
                  AND expiring >= NOW()
                  AND status = "free";

                -- If enough product_serials are available
                IF available_count >= quantity THEN
                    -- Open the cursor
                    OPEN serialCursor;

                    -- Loop through the selected rows
                    fetch_loop: LOOP
                        FETCH serialCursor INTO serialId;

                        IF done = 1 THEN
                            LEAVE fetch_loop;
                        END IF;

                        -- Update the product_serial to "presold"
                        UPDATE product_serials
                        SET status = "presold"
                        WHERE id = serialId;

                        -- Insert the updated serial into temp_stock
                        INSERT INTO temp_stock (order_id, product_id, product_serial_id, created_at)
                        VALUES (orderId, productId, serialId, NOW());
                    END LOOP;

                    -- Close the cursor
                    CLOSE serialCursor;
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('update_product_serials');
    }
};
