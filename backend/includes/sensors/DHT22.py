from includes.includes import getpin

class DHT22:
    def __init__(self, option: str) -> None:

        from adafruit_dht import DHT22
        self.pin = int(option)

        self.device = DHT22(getpin(self.pin, returntype='circuitpython'), use_pulseio=False)  # 26.99ms just this line
        self.retrytime = 1


    def read(self) -> tuple[float, float]:
        """
        reads the DHT22 sensor

        :rtype: Dict[str, Union[int, float], str, Union[int, float]]
        :return: Dict[temp: Union[int, float], rh: Union[int, float]]:
        """
        from time import sleep
        done = False
        while not done:
            try:
                temperature_c = self.device.temperature
                humidity = self.device.humidity  # 379.12ms reading the DHT22 is reeeeeeeeeeally slow

            except RuntimeError:
                # Errors happen fairly often, DHT's are hard to read, just keep going
                sleep(self.retrytime)
                continue

            except Exception as error:
                self.device.exit()
                raise error

            if temperature_c and humidity is not None:
                done = True

        return float(temperature_c), float(humidity)


    def __del__(self):
        self.device.exit()

